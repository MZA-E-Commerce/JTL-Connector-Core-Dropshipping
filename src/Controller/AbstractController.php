<?php

namespace Jtl\Connector\Core\Controller;

use DateTimeZone;
use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractController
{
    /**
     * @var string
     */
    public const CUSTOMER_TYPE_B2B = 'b1d7b4cbe4d846f0b323a9d840800177';

    /**
     * @var string
     */
    public const CUSTOMER_TYPE_B2B_DROPSHIPPING = '323ab1d7bf0b80017719d8404cbe4d46';

    /**
     * @var string
     */
    public const CUSTOMER_TYPE_B2C = 'c2c6154f05b342d4b2da85e51ec805c9';

    /**
     * @var string
     */
    public const CUSTOMER_TYPE_B2B_DS_SHORTCUT = 'MZA B2B-DS';

    public const CUSTOMER_TYPE_B2B_SHORTCUT = 'MZA B2B';

    public const CUSTOMER_TYPE_B2C_SHORTCUT = 'MZA B2C';

    /**
     * @var array
     */
    public const CUSTOMER_TYPE_MAPPINGS = [
        self::CUSTOMER_TYPE_B2B => self::CUSTOMER_TYPE_B2B_SHORTCUT,
        self::CUSTOMER_TYPE_B2B_DROPSHIPPING => self::CUSTOMER_TYPE_B2B_DS_SHORTCUT,
        self::CUSTOMER_TYPE_B2C => self::CUSTOMER_TYPE_B2C_SHORTCUT,
        '' => 'CUSTOMER_TYPE_NOT_SET'
    ];

    /**
     * @var array
     */
    public const CUSTOMER_TYPE_MAPPINGS_REVERSE = [
        self::CUSTOMER_TYPE_B2B_SHORTCUT => self::CUSTOMER_TYPE_B2B,
        self::CUSTOMER_TYPE_B2B_DS_SHORTCUT => self::CUSTOMER_TYPE_B2B_DROPSHIPPING,
        self::CUSTOMER_TYPE_B2C_SHORTCUT => self::CUSTOMER_TYPE_B2C,
        'CUSTOMER_TYPE_NOT_SET' => ''
    ];

    /**
     * @var string
     */
    protected const UPDATE_TYPE_PRODUCT = 'setProductData';

    /**
     * @var string
     */
    protected const UPDATE_TYPE_PRODUCT_STOCK_LEVEL = 'setProductStockLevel';

    /**
     * @var string
     */
    protected const UPDATE_TYPE_PRODUCT_PRICE = 'setProductPrice';

    /**
     * @var string
     */
    protected const UPDATE_TYPE_CUSTOMER_ORDERS = 'geCustomerOrders';

    /**
     * @var string
     */
    const STUECKPREIS = 'stueckpreis';

    /**
     * @var string
     */
    const SONDERPREIS = 'sonderpreis';

    /**
     * @var CoreConfigInterface
     */
    protected CoreConfigInterface $config;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Using direct dependencies for better testing and easier use with a DI container.
     *
     * AbstractController constructor.
     * @param CoreConfigInterface $config
     * @param LoggerInterface $logger
     */
    public function __construct(CoreConfigInterface $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Template‑Method for all Controllers
     *
     * @param AbstractModel ...$models
     * @return AbstractModel[]
     */
    public function push(AbstractModel ...$models): array
    {
        foreach ($models as $i => $model) {
            // Check type
            if (!$model instanceof Product) {
                $this->logger->error('Invalid model type. Expected Product, got ' . get_class($model));
                continue;
            }

            $identity = $model->getId();
            // Check existing mapping
            if ($identity->getEndpoint()) {
                $this->logger->info(\sprintf(
                    'Product already has identity (host=%d endpoint=%d)',
                    $identity->getHost(),
                    $identity->getEndpoint()
                ));
            } else {
                // Get Endpoint ID
                try {
                    $endpointId = $this->getEndpointId($model->getSku());
                    if (empty($endpointId)) {
                        throw new \Exception('Invalid/empty endpoint ID (SKU)');
                    }
                } catch (\Throwable $e) {
                    $this->logger->error('Error fetching Endpoint ID for SKU ' . $model->getSku() . ': ' . $e->getMessage());
                    continue;
                }

                $identity = new Identity($endpointId, $identity->getHost());
                $model->setId($identity);
            }

            // Hook for the update
            try {
                $this->updateModel($model);
            } catch (\Throwable $e) {
                $this->logger->error('Error in updateModel(): ' . $e->getMessage());
            }

            $models[$i] = $model;
        }

        return $models;
    }

    /**
     * @param string $endpointKey
     * @return string
     */
    protected function getEndpointUrl(string $endpointKey): string
    {
        $apiKey = $this->config->get('endpoint.api.key');
        if (empty($apiKey)) {
            throw new \RuntimeException('Endpoint API key is not set');
        }

        $url = $this->config->get('endpoint.api.url');
        return $url . $this->config->get('endpoint.api.endpoints.' . $endpointKey . '.url');
    }

    /**
     * @return HttpClientInterface
     */
    protected function getHttpClient(): HttpClientInterface
    {
        $client = HttpClient::create();
        return $client->withOptions([
            'headers' => [
                'X-Api-Key' => $this->config->get('endpoint.api.key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            //'auth_basic' => [$this->config->get('endpoint.api.auth.username'), $this->config->get('endpoint.api.auth.password')]
        ]);
    }

    /**
     * @param string $sku
     * @return string
     */
    protected function getEndpointId(string $sku): string
    {
        return $sku;

    }

    /**
     * @param Product $product
     * @param string $type
     * @return void
     */
    protected function updateProductEndpoint(Product $product, string $type = self::UPDATE_TYPE_PRODUCT): void
    {
        $httpMethod = $this->config->get('endpoint.api.endpoints.' . $type . '.method');
        $client = $this->getHttpClient();
        $fullApiUrl = $this->getEndpointUrl($type);

        $fullApiUrl = str_replace('{sku}', $product->getSku(), $fullApiUrl);

        $postData = [];
        $postDataPrices = [];
        $priceTypes = $this->config->get('priceTypes');

        switch ($type) {
            case self::UPDATE_TYPE_PRODUCT_STOCK_LEVEL:
                $this->logger->info('Updating product stock level (SKU: ' . $product->getSku() . ')');
                $postData['artikelNr'] = $product->getId()->getEndpoint();
                $postData['lagerbestand'] = $product->getStockLevel();
                break;
            case self::UPDATE_TYPE_PRODUCT_PRICE:
                $this->logger->info('Updating product prices (SKU: ' . $product->getSku() . ')');
                $postDataPrices = $this->getPrices($product, $priceTypes);
                break;
        }

        if (!empty($postDataPrices)) {

            foreach ($postDataPrices as $endpointType => $data) {

                $fullApiUrl1 = str_replace('{endpointType}', $endpointType, $fullApiUrl);

                foreach ($data as $priceType => $jsonData) {

                    $fullApiUrl2 = str_replace('{priceType}', $priceType, $fullApiUrl1);

                    $this->logger->info('API URLS | Method: ' . $httpMethod . ' | URL: ' . $fullApiUrl2 . ' | Data: ' . json_encode($jsonData));

                    $serverName = $_SERVER['SERVER_NAME'] ?? gethostname();
                    if ($serverName == 'jtl-connector.docker') {
                        file_put_contents('/var/www/html/var/log/urls.log', 'API URLS 
                            | Method: ' . $httpMethod . ' 
                            | URL: ' . $fullApiUrl2 . ' 
                            | Data: ' . print_r($jsonData, true) . PHP_EOL . PHP_EOL, FILE_APPEND);
                    } else {
                        file_put_contents('/home/www/p689712/html/jtl-connector-dropshipping/var/log/urls.log', 'API URLS 
                            | Method: ' . $httpMethod . ' 
                            | URL: ' . $fullApiUrl2 . ' 
                            | Data: ' . print_r($jsonData, true) . PHP_EOL . PHP_EOL, FILE_APPEND);
                    }

                    try {
                        $response = $client->request($httpMethod, $fullApiUrl2, ['json' => $jsonData]);
                        $statusCode = $response->getStatusCode();
                        $responseData = $response->toArray();

                        if ($statusCode === 200 && isset($responseData['artikelNr']) && $responseData['artikelNr'] === $product->getSku()) {
                            $this->logger->info('Product price updated successfully (SKU: ' . $product->getSku() . ')');
                            continue;
                        }

                        throw new \RuntimeException('API error: ' . ($data['error'] ?? 'Unknown error'));

                    } catch (TransportExceptionInterface|HttpExceptionInterface|DecodingExceptionInterface $e) {
                        throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), 0, $e);
                    }
                }
            }
        }

        if (!empty($postData)) {
            try {

                $this->logger->info($httpMethod . ' -> ' . $fullApiUrl . ' -> ' . json_encode($postData));

                $response = $client->request($httpMethod, $fullApiUrl, ['json' => $postData]);
                $statusCode = $response->getStatusCode();
                $responseData = $response->toArray();

                if ($statusCode === 200 && isset($responseData['artikelNr']) && $responseData['artikelNr'] === $product->getSku()) {
                    $this->logger->info('Product updated successfully (SKU: ' . $product->getSku() . ')');
                    return;
                }

                throw new \RuntimeException('API error: ' . ($data['error'] ?? 'Unknown error'));

            } catch (TransportExceptionInterface|HttpExceptionInterface|DecodingExceptionInterface $e) {
                throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), 0, $e);
            }
        }
    }

    /**
     * @param Product $model
     * @return void
     */
    abstract protected function updateModel(Product $model): void;

    /**
     * @param Product $product
     * @param array $priceTypes
     * @return array
     */
    private function getPrices(Product $product, array $priceTypes): array
    {
        $result = [];

        // 1) regular prices
        foreach ($product->getPrices() as $priceModel) {
            $priceType = match ($priceModel->getCustomerGroupId()->getEndpoint()) {
                self::CUSTOMER_TYPE_B2B => $priceTypes[self::CUSTOMER_TYPE_B2B_SHORTCUT],
                self::CUSTOMER_TYPE_B2B_DROPSHIPPING => $priceTypes[self::CUSTOMER_TYPE_B2B_DS_SHORTCUT],
                self::CUSTOMER_TYPE_B2C => $priceTypes[self::CUSTOMER_TYPE_B2C_SHORTCUT],
                default => $priceTypes['UPE'], // "Netto VK" field from JTL WaWi
            };
            foreach ($priceModel->getItems() as $item) {
                $result[self::STUECKPREIS][$priceType] = [
                    "value" => $item->getNetPrice(),
                ];
            }
        }

        // 2) Special prices
        foreach ($product->getSpecialPrices() as $specialModel) {
            foreach ($specialModel->getItems() as $item) {

                $priceType = match ($item->getCustomerGroupId()->getEndpoint()) {
                    self::CUSTOMER_TYPE_B2B => $priceTypes[self::CUSTOMER_TYPE_B2B_SHORTCUT],
                    self::CUSTOMER_TYPE_B2B_DROPSHIPPING => $priceTypes[self::CUSTOMER_TYPE_B2B_DS_SHORTCUT],
                    self::CUSTOMER_TYPE_B2C => $priceTypes[self::CUSTOMER_TYPE_B2C_SHORTCUT],
                    default => null,
                };

                if (!$priceType) {
                    continue;
                }

                $from = ($dt = (clone $specialModel->getActiveFromDate())?->setTimezone(new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.') . substr($dt->format('u'), 0, 3) . 'Z';
                $until = ($dt = (clone $specialModel->getActiveUntilDate())?->setTimezone(new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.') . substr($dt->format('u'), 0, 3) . 'Z';

                $result[self::SONDERPREIS][$priceType] = [
                    "value" => $item->getPriceNet(),
                    "von" => $from,
                    "bis" => $until
                ];
            }
        }

        return $result;
    }
}
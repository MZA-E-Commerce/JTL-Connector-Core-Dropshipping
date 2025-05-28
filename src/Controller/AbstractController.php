<?php

namespace Jtl\Connector\Core\Controller;

use DateTimeZone;
use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\ProductPrice;
use Jtl\Connector\Core\Model\QueryFilter;
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
    public const CUSTOMER_TYPE_B2C = 'c2c6154f05b342d4b2da85e51ec805c9';

    /**
     * @var array
     */
    public const CUSTOMER_TYPE_MAPPINGS = [
        self::CUSTOMER_TYPE_B2B => 'B2B',
        self::CUSTOMER_TYPE_B2C => 'B2C',
        '' => 'CUSTOMER_TYPE_NOT_SET'
    ];

    /**
     * @var array
     */
    public const CUSTOMER_TYPE_MAPPINGS_REVERSE = [
        'B2B' => self::CUSTOMER_TYPE_B2B,
        'B2C' => self::CUSTOMER_TYPE_B2C,
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
    protected const CUSTOMER_TYPE_DEFAULT = self::CUSTOMER_TYPE_B2C;

    /**
     * @var string
     */
    protected const PRICE_TYPE_RETAIL_NET = 'retail_price_net';

    /**
     * @var string
     */
    protected const PRICE_TYPE_REGULAR = 'regular';

    /**
     * @var string
     */
    protected const PRICE_TYPE_SPECIAL = 'special';

    /**
     * @var string
     */
    protected const PRICE_TYPE_VK20 = 'VK20';

    /**
     * @var string
     */
    protected const PRICE_TYPE_VK21 = 'VK21';

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

        $postData = [];
        $postDataPrices = [];

        switch ($type) {
            case self::UPDATE_TYPE_PRODUCT_STOCK_LEVEL:
                $this->logger->info('Updating product stock level (SKU: ' . $product->getSku() . ')');
                $postData['artikelNr'] = $product->getId()->getEndpoint();
                $postData['lagerbestand'] = $product->getStockLevel();
                break;
            case self::UPDATE_TYPE_PRODUCT_PRICE:
                $this->logger->info('Updating product price (SKU: ' . $product->getSku() . ')');
                $priceType = $this->config->get('endpoint.api.endpoints.' . $type . '.priceType', self::PRICE_TYPE_VK21);
                $postDataPrices = $this->getPrices($product, $priceType);
                break;
            case self::UPDATE_TYPE_PRODUCT: // Check JTL WaWi setting "Artikel komplett senden"!
                $this->logger->info('Updating product (SKU: ' . $product->getSku() . ')');
                $priceType = $this->config->get('endpoint.api.endpoints.' . $type . '.priceType', self::PRICE_TYPE_VK21);
                $postDataPrices = $this->getPrices($product, $priceType);
                break;
        }

        if (!empty($postDataPrices)) {
            file_put_contents(Application::LOG_DIR . '/postData_' . $type . '.log', $httpMethod . ' -> ' . $fullApiUrl . ' -> ' . json_encode($postDataPrices) . PHP_EOL . PHP_EOL);
            foreach ($postDataPrices as $postData) {
                try {
                    $response = $client->request($httpMethod, $fullApiUrl, ['json' => $postData]);
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

        if (!empty($postData)) {
            file_put_contents(Application::LOG_DIR . '/postData_' . $type . '.log', $httpMethod . ' -> ' . $fullApiUrl . ' -> ' . json_encode($postData) . PHP_EOL . PHP_EOL);
            try {
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
     * @param string $priceType
     * @return array
     */
    private function getPrices(Product $product, string $priceType = 'VK21'): array
    {

        $result = [];

        // 1) regular prices
        foreach ($product->getPrices() as $priceModel) {
            if ($priceModel->getCustomerGroupId()->getEndpoint() == self::CUSTOMER_TYPE_B2B || empty($priceModel->getCustomerGroupId()->getEndpoint())) {
                // Skip empty or B2B prices
                continue;
            }
            foreach ($priceModel->getItems() as $item) {
                $result[] = [
                    "vkId"=> 0,
                    "artikelNr" => $product->getSku(),
                    "bezeichnung" => $priceType,
                    "stueckpreis" => $item->getNetPrice(),
                    "sonderpreis" => 0,
                    "sonderpreisVon" => "",
                    "sonderpreisBis" => ""
                ];
            }
        }

        // 2) Special prices
        foreach ($product->getSpecialPrices() as $specialModel) {
            foreach ($specialModel->getItems() as $item) {
                // Transfer only B2C prices
                if ($item->getCustomerGroupId()->getEndpoint() == self::CUSTOMER_TYPE_B2B || empty($item->getCustomerGroupId()->getEndpoint())) {
                    // Skip empty or B2B prices
                    continue;
                }

                $from = ($dt = (clone $specialModel->getActiveFromDate())?->setTimezone(new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.') . substr($dt->format('u'), 0, 3) . 'Z';
                $until = ($dt = (clone $specialModel->getActiveUntilDate())?->setTimezone(new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.') . substr($dt->format('u'), 0, 3) . 'Z';

                $result[] = [
                    "vkId"=> 0,
                    "artikelNr" => $product->getSku(),
                    "bezeichnung" => $priceType,
                    "stueckpreis" => 0,
                    "sonderpreis" => $item->getPriceNet(),
                    "sonderpreisVon" => $from,
                    "sonderpreisBis" => $until
                ];
            }
        }

        return $result;
    }
}
<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Subscriber;

use Jtl\Connector\Core\Definition\Action;
use Jtl\Connector\Core\Definition\Controller;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Event\RpcEvent;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\ProductStockLevel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RequestParamsTransformSubscriber
 *
 * @package Jtl\Connector\Core\Subscriber
 */
class RequestParamsTransformSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Event::createRpcEventName(Event::BEFORE) => [
                [
                    'transformRequestParams',
                    10000,
                ],
            ],
        ];
    }

    /**
     * @param RpcEvent $event
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function transformRequestParams(RpcEvent $event): void
    {
        if ($event->getAction() === Action::PUSH) {
            $eventData = $event->getData();
            if (!\is_array($eventData)) {
                throw new \RuntimeException('$data must be type array.');
            }
            /** @var array<int, array<string, mixed>> $eventData */

            $data = match ($event->getController()) {
                Controller::PRODUCT             => $this->transformProductData($eventData),
                Controller::PRODUCT_PRICE       => $this->transformProductPriceData($eventData),
                Controller::PRODUCT_STOCK_LEVEL => $this->transformProductStockLevelData($eventData),
                default                         => $eventData,
            };

            $event->setData($data);
        }
    }

    /**
     * @param array<int, array<string, mixed>> $products
     *
     * @return array<int, array<string, mixed>>
     * @throws \RuntimeException
     */
    public function transformProductData(array $products): array
    {
        foreach ($products as $i => $product) {
            /** @var array<string, mixed> $product */
            if (
                \array_key_exists('stockLevel', $product)
                && \is_array($product['stockLevel'])
                && isset($product['stockLevel']['stockLevel'])
                && \array_key_exists('stockLevel', $product['stockLevel'])
            ) {
                $products[$i]['stockLevel'] = $product['stockLevel']['stockLevel'];
            }

            if (isset($product['prices']) && \is_array($product['prices'])) {
                if (!isset($products[$i]['prices'])) {
                    throw new \RuntimeException('unexpected array structure.');
                }
                foreach ($products[$i]['prices'] as $j => $productPrice) {
                    /** @var array<string, mixed> $productPrice */
                    $products[$i]['prices'][$j] = self::sortProductPriceItems($productPrice);
                }
            }
        }

        return $products;
    }

    /**
     * @param array<string, mixed> $productPrice
     *
     * @return array<string, mixed>
     */
    protected static function sortProductPriceItems(array $productPrice): array
    {
        if (isset($productPrice['items'])) {
            /** @var array<string, numeric> $items */
            $items = $productPrice['items'];
            \usort($items, static function ($a, $b) {
                return ($a['quantity'] ?? 0) - ($b['quantity'] ?? 0);
            });
            $productPrice['items'] = $items;
        }

        return $productPrice;
    }

    /**
     * @param array<array<string, mixed>> $productPrices
     *
     * @return array<int, mixed>
     * @throws \InvalidArgumentException
     */
    public function transformProductPriceData(array $productPrices): array
    {
        $products = [];

        foreach ($productPrices as $productPrice) {
            /** @var array{string, int} $productId */
            $productId = $productPrice['productId'];
            $hostId    = self::extractHostId($productId);

            if (!isset($products[$hostId])) {
                $product = [
                    'id'         => $productPrice['productId'] ?? null,
                    'sku'        => $productPrice['sku'] ?? '',
                    'vat'        => $productPrice['vat'] ?? 0.,
                    'taxClassId' => $productPrice['taxClassId'] ?? null,
                ];

                $products[$hostId] = $product;
            }

            $products[$hostId]['prices'][] = self::sortProductPriceItems($productPrice);
        }

        return \array_values($products);
    }

    /**
     * @param array{string, int} $identity - [endpointId, hostId]
     *
     * @return int
     * @throws \InvalidArgumentException
     */
    protected static function extractHostId(array $identity): int
    {
        return Identity::fromArray($identity)->getHost();
    }

    /**
     * @param array<int, array<string, mixed>> $productStockLevel
     *
     * @return array<int, array{id: array{0: string, 1: int}|ProductStockLevel|null, sku: string, stockLevel: float}>
     */
    public function transformProductStockLevelData(array $productStockLevel): array
    {
        $products = [];
        foreach ($productStockLevel as $stockLevel) {
            /** @var array{0: string, 1: int}|ProductStockLevel|null $id */
            $id = $stockLevel['productId'] ?? null;
            /** @var string $sku */
            $sku = $stockLevel['sku'] ?? '';
            /** @var float $sl */
            $sl         = $stockLevel['stockLevel'] ?? 0.;
            $product    = [
                'id'         => $id,
                'sku'        => $sku,
                'stockLevel' => $sl
            ];
            $products[] = $product;
        }

        return $products;
    }
}

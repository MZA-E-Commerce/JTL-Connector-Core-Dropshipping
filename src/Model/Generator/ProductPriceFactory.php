<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Faker\Extension\ExtensionNotFound;
use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Model\ProductPrice;

class ProductPriceFactory extends AbstractModelFactory
{
    protected bool $withBulkPrices = false;

    /**
     * @param bool $withBulkPrices
     *
     * @return $this
     */
    public function setWithBulkPrices(bool $withBulkPrices): self
    {
        $this->withBulkPrices = $withBulkPrices;

        return $this;
    }

    /**
     * @return array{
     *     id:              array<int, int|string>,
     *     customerGroupId: array<int, int|string>,
     *     productId:       array<int, int|string>,
     *     items:           array<mixed>
     * }
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        return [
            'id'              => $this->makeIdentityArray(IdentityType::PRODUCT_PRICE),
            'customerGroupId' => $this->makeIdentityArray(IdentityType::CUSTOMER_GROUP),
            //'customerId' => null,
            'productId'       => $this->makeIdentityArray(IdentityType::PRODUCT),
            'items'           => $this->makeItemsArray($this->withBulkPrices),
        ];
    }

    /**
     * @param bool $withBulkPrices
     *
     * @return array<mixed>
     * @throws \Exception
     */
    public function makeItemsArray(bool $withBulkPrices = false): array
    {
        $items = [$this->makeItemArray(['quantity' => 0, 'netPrice' => $this->faker->randomFloat(4, 1)])];

        if ($withBulkPrices === true) {
            $pricesCount = \random_int(1, \random_int(1, 30));
            $maxQuantity = \random_int($pricesCount, \random_int($pricesCount, 500)); // @phpstan-ignore-line
            $step        = (int)\floor($maxQuantity / $pricesCount);
            $priceStep   = \floor($items[0]['netPrice'] / $pricesCount);

            $quantity = 0;
            for ($i = 0; $i < $pricesCount; $i++) {
                $quantity += $step;
                $minPrice  = $items[$i]['netPrice'] - $priceStep;
                if ($minPrice > $items[$i]['netPrice'] || $minPrice < 0) {
                    $minPrice = (($items[$i]['netPrice'] - 0.1) / 2);
                }
                $price   = $this->faker->randomFloat(4, $minPrice, $items[$i]['netPrice']);
                $items[] = $this->makeItemArray(['quantity' => $quantity, 'netPrice' => $price]);
            }
        }

        return $items;
    }

    /**
     * @param mixed[] $override
     *
     * @return mixed[]
     * @throws ExtensionNotFound
     */
    public function makeItemArray(array $override = []): array
    {
        return \array_merge([
                                'quantity' => $this->faker->numberBetween(),
                                'netPrice' => $this->faker->randomFloat(4, 1),
                            ], $override);
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductPrice::class;
    }
}

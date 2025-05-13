<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Model\Product;

class ProductFactory extends AbstractModelFactory
{
    protected bool $withBasePrice = false;

    /**
     * @param array<int, array<string, mixed>>|null $i18ns
     *
     * @return object[]
     * @throws \Exception
     */
    public function makeOneProductVariant(?array $i18ns = null): array
    {
        $data = $this->makeOneProductVariantArray($i18ns);
        return $this->make(\count($data), $data);
    }

    /**
     * @param array<int, array<string, mixed>>|null $i18ns
     *
     * @return array<mixed>
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function makeOneProductVariantArray(?array $i18ns = null): array
    {
        $variationsQuantity = 2;
        if (\is_null($i18ns)) {
            /** @var I18nFactory $i18nFactory */
            $i18nFactory = $this->getFactory('I18n');
            $i18ns       = $i18nFactory->makeArray(\random_int(1, 3));
        }

        /** @var ProductI18nFactory $productI18nFactory */
        $productI18nFactory = $this->getFactory('ProductI18n');
        $productI18ns       = $productI18nFactory->makeArray(\count($i18ns), $i18ns);
        $variantsQuantity   = 1;
        $variations         = [];
        for ($i = 0; $i < $variationsQuantity; $i++) {
            $variationI18ns = \array_map(function (array $i18n) {
                return $this->getFactory('ProductVariationI18n')->makeOneArray($i18n);
            }, $i18ns);

            $values            = [];
            $valuesQuantity    = \random_int(1, 10);
            $variantsQuantity *= $valuesQuantity;
            for ($j = 0; $j < $valuesQuantity; $j++) {
                $valueI18ns = [];
                foreach ($i18ns as $i18n) {
                    $valueI18ns[] = $this->getFactory('ProductVariationValueI18n')->makeOneArray($i18n);
                }

                $values[] = $this->getFactory('ProductVariationValue')
                                 ->makeOneArray([
                                                    'i18ns' => $valueI18ns,
                                                    'sort'  => $j,
                                                ]);
            }

            $variations[] = $this->getFactory('ProductVariation')
                                 ->makeOneArray([
                                                    'i18ns'  => $variationI18ns,
                                                    'values' => $values,
                                                    'sort'   => $i,
                                                ]);
        }

        $parentId = $this->getFactory('Identity')->makeOneArray();
        $variants = [
            $this->makeOneArray(
                [
                    'id'              => $parentId,
                    'isMasterProduct' => true,
                    'variations'      => $variations,
                    'sort'            => 0,
                    'i18ns'           => $productI18ns,
                ]
            )

        ];

        $i = 0;
        foreach ($variations[0]['values'] as $firstValue) { //@phpstan-ignore-line
            foreach ($variations[1]['values'] as $secondValue) { //@phpstan-ignore-line
                $variants[] = $this->makeOneArray([
                                                      'masterProductId' => $parentId,
                                                      'isMasterProduct' => false,
                                                      'variations'      => [
                                                          \array_merge($variations[0], ['values' => [$firstValue]]),
                                                          \array_merge($variations[1], ['values' => [$secondValue]]),
                                                      ],
                                                      'sort'            => ++$i,
                                                      'prices'          => $variants[0]['prices'],
                                                      'i18ns'           => $productI18ns,
                                                  ]);
            }
        }

        return $variants;
    }

    /**
     * @return array<string, mixed>
     * @throws \RuntimeException
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        /** @var IdentityFactory $identityFactory */
        $identityFactory = $this->getFactory('Identity');

        $units = ['mm', 'cm', 'm', 'km'];

        /** @var TaxRateFactory $taxRateFactory */
        $taxRateFactory = $this->getFactory('TaxRate');
        $taxRates       = $taxRateFactory->makeArray(\random_int(1, 30));
        $taxRateFactory->clearUsedCountries();

        if (($taxRatesMaxCount = \count($taxRates) - 1) < 0) {
            throw new \RuntimeException('taxRatesMaxCount must not be smaller than zero.');
        }

        if (!($productPriceFactory = $this->getFactory('ProductPrice')) instanceof ProductPriceFactory) {
            throw new \RuntimeException('productPriceFactory must be instance of ProductPriceFactory!');
        }
        /** @var ProductPriceFactory $productPriceFactory */

        return [
            'basePriceUnitId'             => $identityFactory->makeOneArray(),
            'manufacturerId'              => $this->makeIdentityArray(IdentityType::MANUFACTURER),
            'measurementUnitId'           => $identityFactory->makeOneArray(),
            'partsListId'                 => $identityFactory->makeOneArray(),
            'productTypeId'               => $identityFactory->makeOneArray(),
            'shippingClassId'             => $identityFactory->makeOneArray(),
            'unitId'                      => $identityFactory->makeOneArray(),
            'asin'                        => $this->faker->word,
            'availableFrom'               => \random_int(0, 1) === 1 ? $this->faker->iso8601 : null,
            'measurementQuantity'         => $this->faker->randomFloat(2, 1),
            'measurementUnitCode'         => $this->faker->randomElement($units),
            'basePriceDivisor'            => 0.0,
            'basePriceFactor'             => 0.0,
            'basePriceQuantity'           => $this->faker->randomFloat(3, 1),
            'basePriceUnitCode'           => $this->faker->randomElement($units),
            'basePriceUnitName'           => $this->faker->word,
            'considerBasePrice'           => false,
            'considerStock'               => $this->faker->boolean,
            'considerVariationStock'      => $this->faker->boolean,
            'creationDate'                => $this->dateBetween(
                \sprintf(
                    '-%d %s',
                    \random_int(1, 60),
                    $this->faker->randomKey(['days', 'years', 'hours', 'minutes', 'seconds'])
                )
            ),
            'ean'                         => $this->faker->ean8, // @phpstan-ignore-line
            'epid'                        => $this->faker->uuid,
            'hazardIdNumber'              => $this->faker->sha1,
            'height'                      => $this->faker->randomFloat(),
            'isActive'                    => $this->faker->boolean,
            'isBatch'                     => $this->faker->boolean,
            'isBestBefore'                => $this->faker->boolean,
            'isbn'                        => $this->faker->isbn13, // @phpstan-ignore-line
            'isDivisible'                 => $this->faker->boolean,
            //'isMasterProduct' => false,
            'isNewProduct'                => $this->faker->boolean,
            'isSerialNumber'              => $this->faker->boolean,
            'isTopProduct'                => $this->faker->boolean,
            'keywords'                    => $this->faker->words(\random_int(0, 10), true),
            'length'                      => $this->faker->randomFloat(),
            'manufacturerNumber'          => $this->faker->word(),
            //'manufacturer' => null,
            'minBestBeforeDate'           => $this->dateBetween(
                \sprintf(
                    '+%d %s',
                    \random_int(0, 60),
                    $this->faker->randomKey(['days', 'years', 'hours', 'minutes', 'seconds'])
                )
            ),
            'minimumOrderQuantity'        => $this->faker->randomFloat(2),
            'minimumQuantity'             => $this->faker->randomFloat(2),
            'modified'                    => $this->faker->iso8601,
            'newReleaseDate'              => $this->faker->iso8601,
            'nextAvailableInflowDate'     => $this->faker->iso8601,
            'nextAvailableInflowQuantity' => $this->faker->randomFloat(3),
            'note'                        => $this->faker->sentence,
            'originCountry'               => $this->faker->languageCode,
            'packagingQuantity'           => $this->faker->randomFloat(2),
            'permitNegativeStock'         => $this->faker->boolean,
            'productWeight'               => $this->faker->randomFloat(4),
            'purchasePrice'               => $this->faker->randomFloat(4),
            'recommendedRetailPrice'      => $this->faker->randomFloat(),
            'serialNumber'                => $this->faker->uuid,
            'shippingWeight'              => $this->faker->randomFloat(3),
            'sku'                         => $this->faker->uuid,
            //'sort' => 0,
            'stockLevel'                  => null,
            'supplierDeliveryTime'        => $this->faker->numberBetween(0, 366),
            'supplierStockLevel'          => $this->faker->randomFloat(),
            'taric'                       => $this->faker->word,
            'taxClassId'                  => $this->makeIdentityArray(IdentityType::TAX_CLASS),
            'unNumber'                    => $this->faker->word,
            'upc'                         => $this->faker->uuid,
            'vat'                         => $taxRates[\random_int(0, $taxRatesMaxCount)]['rate'],
            'width'                       => $this->faker->randomFloat(2),
            'attributes'                  => $this->getFactory('TranslatableAttribute')->makeArray(\random_int(1, 3)),
            'categories'                  => $this->getFactory('Product2Category')->makeArray(\random_int(1, 3)),
            'checksums'                   => $this->getFactory('Checksum')->makeArray(\random_int(1, 3)),
            'configGroups'                => $this->getFactory('ProductConfigGroup')->makeArray(\random_int(1, 3)),
            'customerGroupPackagingQuantities' => $this->getFactory('CustomerGroupPackagingQuantity')
                ->makeArray(\random_int(1, 3)),
            //'fileDownloads' => [],
            'i18ns'                       => [$this->getFactory('ProductI18n')->makeOneArray()],
            'invisibilities'              => $this->getFactory('ProductInvisibility')->makeArray(\random_int(1, 3)),
            'mediaFiles'                  => [],
            'partsLists'                  => $this->getFactory('PartsList')->makeArray(\random_int(1, 3)),
            'prices'                      => [
                $productPriceFactory
                    ->setWithBulkPrices(\random_int(0, 1) === 1)
                    ->makeOneArray(['customerGroupId' => $identityFactory->makeOneArray([1 => 0])])
            ],
            'specialPrices'               => [],
            'specifics'                   => [],
            'taxRates'                    => $taxRates,
            'variations'                  => [],
            'warehouseInfo'               => $this->getFactory('ProductWarehouseInfo')->makeArray(\random_int(1, 3)),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Product::class;
    }
}

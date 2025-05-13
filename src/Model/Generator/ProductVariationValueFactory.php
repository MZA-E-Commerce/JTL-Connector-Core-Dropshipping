<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductVariationValue;

class ProductVariationValueFactory extends AbstractModelFactory
{
    /**
     * @return array{sort: int, i18ns: array<int, array<int|string, mixed>>}
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        /** @var ProductVariationValueI18nFactory $i18nsFactory */
        $i18nsFactory = $this->getFactory('ProductVariationValueI18n');
        $i18ns        = $i18nsFactory->makeArray(\random_int(1, 5));

        return [
            'sort'  => $this->faker->numberBetween(),
            'i18ns' => $i18ns,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductVariationValue::class;
    }
}

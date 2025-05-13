<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductVariationValueI18n;

class ProductVariationValueI18nFactory extends AbstractModelFactory
{
    /**
     * @return array{languageIso: string, name: string}
     */
    protected function makeFakeArray(): array
    {

        return [
            'languageIso' => $this->faker->languageCode,
            'name'        => $this->faker->text(80),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductVariationValueI18n::class;
    }
}

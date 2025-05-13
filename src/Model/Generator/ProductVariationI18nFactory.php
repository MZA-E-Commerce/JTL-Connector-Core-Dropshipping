<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductVariationI18n;

class ProductVariationI18nFactory extends AbstractModelFactory
{
    /**
     * @return array{name: string, languageIso: string}
     */
    protected function makeFakeArray(): array
    {
        return [
            'name'        => $this->faker->text,
            'languageIso' => $this->faker->languageCode,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductVariationI18n::class;
    }
}

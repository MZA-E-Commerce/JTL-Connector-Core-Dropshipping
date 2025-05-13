<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ManufacturerI18n;

/**
 * Class ManufacturerI18nFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ManufacturerI18nFactory extends AbstractModelFactory
{
    /**
     * @param array<string, mixed>|array{} $override
     *
     * @return array<string, mixed>
     */
    public function makeFakeArray(array $override = []): array
    {
        return \array_merge(
            [
                'description'     => $this->faker->text(),
                'metaDescription' => $this->faker->text(),
                'metaKeywords'    => $this->faker->text(),
                'titleTag'        => $this->faker->text(),
                'languageIso'     => $this->faker->languageCode,
            ],
            $override
        );
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ManufacturerI18n::class;
    }
}

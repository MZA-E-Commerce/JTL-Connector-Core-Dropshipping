<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductI18n;

class ProductI18nFactory extends AbstractI18nFactory
{
    /**
     * @return array{
     *     deliveryStatus:      string,
     *     description:         string,
     *     measurementUnitName: string,
     *     metaDescription:     string,
     *     metaKeywords:        string,
     *     name:                string,
     *     shortDescription:    string,
     *     titleTag:            string,
     *     unitName:            string,
     *     urlPath:             string
     * }
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        /** @var string $measurementUnitName */
        $measurementUnitName = $this->faker->randomElement(['Kilogram', 'Centimeter', '']);
        /** @var string $unitName */
        $unitName = $this->faker->randomElement(['Piece', 'Pack', '']);
        /** @var string $metaKeywords */
        $metaKeywords = $this->faker->words(\random_int(0, 5), true);

        return [
            'deliveryStatus'      => $this->faker->word,
            'description'         => $this->faker->sentence,
            'measurementUnitName' => $measurementUnitName,
            'metaDescription'     => $this->faker->text,
            'metaKeywords'        => $metaKeywords,
            'name'                => $this->faker->word,
            'shortDescription'    => $this->faker->text,
            'titleTag'            => $this->faker->word,
            'unitName'            => $unitName,
            'urlPath'             => $this->faker->url,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductI18n::class;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CategoryI18n;

/**
 * Class CategoryI18nFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CategoryI18nFactory extends AbstractModelFactory
{
    /**
     * @return array<string, string>
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        /** @var string $metaKeywords */
        $metaKeywords = $this->faker->words(\random_int(0, 5), true);

        return [
            'description'     => $this->faker->sentence,
            'metaDescription' => $this->faker->text,
            'metaKeywords'    => $metaKeywords,
            'name'            => $this->faker->word,
            'titleTag'        => $this->faker->word,
            'urlPath'         => $this->faker->url,
            'languageIso'     => $this->faker->languageCode,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CategoryI18n::class;
    }
}

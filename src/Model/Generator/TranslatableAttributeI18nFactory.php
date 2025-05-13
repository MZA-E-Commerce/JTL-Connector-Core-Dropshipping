<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\TranslatableAttributeI18n;

/**
 * Class TranslatableAttributeI18nFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class TranslatableAttributeI18nFactory extends AbstractModelFactory
{
    /**
     * @return array{name: string, value: mixed, languageIso: string}
     */
    protected function makeFakeArray(): array
    {
        return [
            'name'        => $this->faker->word,
            'value'       => $this->faker
                ->randomElement([
                                    true,
                                    false,
                                    $this->faker->words(3, true),
                                ]),
            'languageIso' => $this->faker->languageCode,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return TranslatableAttributeI18n::class;
    }
}

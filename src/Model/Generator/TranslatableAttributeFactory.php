<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\TranslatableAttribute;
use RuntimeException;

/**
 * Class TranslatableAttributeFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class TranslatableAttributeFactory extends AbstractModelFactory
{
    /**
     * @return array<string, bool|array<int, mixed>>
     * @throws RuntimeException
     */
    protected function makeFakeArray(): array
    {

        return [
            'isTranslated'     => $this->faker->boolean,
            'isCustomProperty' => $this->faker->boolean,
            'i18ns'            => $this->getFactory('TranslatableAttributeI18n')->makeArray(3),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return TranslatableAttribute::class;
    }
}

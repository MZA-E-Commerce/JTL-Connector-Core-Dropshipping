<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\Specific;
use RuntimeException;

class SpecificFactory extends AbstractModelFactory
{
    /**
     * @return array{sort: 1, isGlobal: bool, type: mixed, i18ns: array<mixed[]>, values: array<mixed[]>}
     * @throws RuntimeException
     */
    protected function makeFakeArray(): array
    {

        return [
            'sort'     => 1,
            'isGlobal' => $this->faker->boolean,
            'type'     => $this->faker->randomElement(
                [
                    Specific::TYPE_IMAGE,
                    Specific::TYPE_IMAGE_TEXT,
                    Specific::TYPE_SELECT,
                    Specific::TYPE_TEXT,
                ]
            ),
            'i18ns'    => [$this->getFactory('SpecificI18n')->makeOneArray()],
            'values'   => [$this->getFactory('SpecificValue')->makeOneArray()],
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Specific::class;
    }
}

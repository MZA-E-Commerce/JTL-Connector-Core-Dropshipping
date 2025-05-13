<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\SpecificValue;
use RuntimeException;

class SpecificValueFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     * @throws RuntimeException
     */
    protected function makeFakeArray(): array
    {
        return [
            'sort'  => 1,
            'i18ns' => [$this->getFactory('SpecificValueI18n')->makeOneArray()],
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return SpecificValue::class;
    }
}

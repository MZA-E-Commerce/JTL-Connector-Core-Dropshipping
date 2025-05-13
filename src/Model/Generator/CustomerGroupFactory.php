<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CustomerGroup;

/**
 * Class CustomerGroupFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CustomerGroupFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        return [
            'id' => $this->getFactory('Identity')->makeOneArray(),
            'applyNetPrice' => $this->faker->boolean,
            'discount' => $this->faker->randomFloat(2, 0, 10),
            'isDefault' => $this->faker->boolean,
            'attributes' => $this->getFactory('TranslatableAttribute')->makeArray(\random_int(1, 3)),
            'i18ns' => $this->getFactory('CustomerGroupI18n')->makeArray(\random_int(1, 3))
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CustomerGroup::class;
    }
}

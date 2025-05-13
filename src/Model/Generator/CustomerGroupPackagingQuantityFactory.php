<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CustomerGroupPackagingQuantity;

/**
 * Class CustomerGroupPackagingQuantityFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CustomerGroupPackagingQuantityFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'customerGroupId' => $this->getFactory('Identity')->makeOneArray(),
            'minimumOrderQuantity' => $this->faker->numberBetween(1, 100),
            'packagingQuantity' => $this->faker->numberBetween(1, 100)
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CustomerGroupPackagingQuantity::class;
    }
}

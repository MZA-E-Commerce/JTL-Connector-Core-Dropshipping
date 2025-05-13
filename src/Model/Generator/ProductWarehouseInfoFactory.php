<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductWarehouseInfo;

/**
 * Class ProductWarehouseInfoFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ProductWarehouseInfoFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'warehouseId' => $this->getFactory('Identity')->makeOneArray(),
            'inflowQuantity' => $this->faker->numberBetween(1, 100),
            'stocklevel' => $this->faker->numberBetween(1, 100)
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductWarehouseInfo::class;
    }
}

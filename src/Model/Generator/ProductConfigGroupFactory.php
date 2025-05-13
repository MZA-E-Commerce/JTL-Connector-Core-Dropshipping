<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductConfigGroup;

/**
 * Class ProductConfigGroupFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ProductConfigGroupFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'configGroupId' => $this->getFactory('Identity')->makeOneArray(),
            'sort' => $this->faker->numberBetween(1, 10)
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductConfigGroup::class;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CategoryCustomerGroup;

/**
 * Class CategoryCustomerGroupFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CategoryCustomerGroupFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        return [
            'customerGroupId' => $this->getFactory('Identity')->makeOneArray(),
            'discount' => $this->faker->randomFloat(2, 0, 10)
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CategoryCustomerGroup::class;
    }
}

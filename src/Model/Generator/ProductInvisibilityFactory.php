<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductInvisibility;

/**
 * Class ProductInvisibilityFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ProductInvisibilityFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'customerGroupId' => $this->getFactory('Identity')->makeOneArray(),
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductInvisibility::class;
    }
}

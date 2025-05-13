<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\ProductAttribute;

class ProductAttributeFactory extends TranslatableAttributeFactory
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return ProductAttribute::class;
    }
}

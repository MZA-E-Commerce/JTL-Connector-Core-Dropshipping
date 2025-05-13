<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CategoryInvisibility;

/**
 * Class CategoryInvisibilityFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CategoryInvisibilityFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'customerGroupId' => $this->getFactory('Identity')->makeOneArray()
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CategoryInvisibility::class;
    }
}

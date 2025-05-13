<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\PartsList;

/**
 * Class PartsListFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class PartsListFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'productId' => $this->getFactory('Identity')->makeOneArray(),
            'quantity' => $this->faker->numberBetween(1, 100)
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return PartsList::class;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\Checksum;

/**
 * Class ChecksumFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ChecksumFactory extends AbstractModelFactory
{
    /**
     * @return array<string, mixed>
     */
    protected function makeFakeArray(): array
    {
        return [
            'foreignKey' => $this->getFactory('Identity')->makeOneArray(),
            'endpoint' => $this->faker->uuid,
            'hasChanged' => false,
            'host' => (string)$this->faker->numberBetween(1),
            'type' => Checksum::TYPE_VARIATION
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Checksum::class;
    }
}

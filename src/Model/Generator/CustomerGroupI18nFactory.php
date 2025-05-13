<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CustomerGroupI18n;

/**
 * Class CustomerGroupI18nFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CustomerGroupI18nFactory extends AbstractModelFactory
{
    /**
     * @return array<string, string>
     */
    protected function makeFakeArray(): array
    {
        return [
            'name' => $this->faker->name
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CustomerGroupI18n::class;
    }
}

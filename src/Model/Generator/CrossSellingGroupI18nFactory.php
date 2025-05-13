<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Model\CrossSellingGroupI18n;

class CrossSellingGroupI18nFactory extends AbstractI18nFactory
{
    /**
     * @return array{description: string, name: string}
     */
    protected function makeFakeArray(): array
    {
        return [
            'description' => $this->faker->text,
            'name'        => $this->faker->name,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return CrossSellingGroupI18n::class;
    }
}

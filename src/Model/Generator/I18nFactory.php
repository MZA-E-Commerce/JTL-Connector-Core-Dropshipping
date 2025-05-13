<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Exception\NotImplementedException;

class I18nFactory extends AbstractI18nFactory
{
    /**
     * @return array{}
     */
    protected function makeFakeArray(): array
    {
        return [];
    }

    // phpcs:disable
    /**
     * @return string
     * @throws NotImplementedException
     */
    protected function getModelClass(): string
    {
        throw new NotImplementedException('This factory can be used for making an array only');
    }
    // phpcs:enable
}

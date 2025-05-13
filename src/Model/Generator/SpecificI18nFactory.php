<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Model\SpecificI18n;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

class SpecificI18nFactory extends AbstractI18nFactory
{
    /**
     * @return array<string, string>
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    protected function makeFakeArray(): array
    {
        return [
            'name' => Validate::checkGeneratorAndNotNull($this->faker)->word,
        ];
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return SpecificI18n::class;
    }
}

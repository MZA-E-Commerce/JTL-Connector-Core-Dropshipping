<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Model\Manufacturer;
use RuntimeException;

/**
 * Class ManufacturerFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class ManufacturerFactory extends AbstractModelFactory
{
    protected int $sort = 1;

    /**
     * @param array<string, mixed> $override
     *
     * @return array<string, mixed>
     * @throws CaseConverterException
     * @throws RuntimeException
     * @throws RuntimeException
     */
    public function makeFakeArray(array $override = []): array
    {
        return \array_merge(
            [
                'id'    => $this->getFactory(
                    'Identity'
                )->makeOneArray(),
                'name'  => $this->faker->text(
                    40
                ),
                'sort'  => $this->sort++,
                'i18ns' => $this->getFactory(
                    'ManufacturerI18n'
                )->makeArray(
                    3
                ),
            ],
            $override
        );
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Manufacturer::class;
    }
}

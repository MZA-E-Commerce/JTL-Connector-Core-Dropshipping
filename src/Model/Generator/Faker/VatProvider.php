<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator\Faker;

use Faker\Provider\Base;
use InvalidArgumentException;

/**
 * Class VatProvider
 *
 * @package Jtl\Connector\Core\Model\Generator\Faker
 */
class VatProvider extends Base
{
    /**
     * @param bool $spacedNationalPrefix
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function vat(bool $spacedNationalPrefix = false): string
    {
        $prefix = $spacedNationalPrefix ? 'DE ' : 'DE';

        return \sprintf('%s%d', $prefix, self::randomNumber(9, true));
    }
}

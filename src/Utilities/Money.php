<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Utilities;

class Money
{
    /**
     * @param $net
     * @param $vat
     *
     * @return float
     * @deprecated since 5.2 use Money::gross() instead.
     */
    //phpcs:ignore
    public static function AsGross($net, $vat): float // @phpstan-ignore-line
    {
        return self::gross($net, $vat);
    }

    /**
     * @param numeric|numeric-string $net
     * @param numeric|numeric-string $vat
     *
     * @return float
     */
    public static function gross(float|int|string $net, float|int|string $vat): float
    {
        $vat = (float)$vat;
        $net = (float)$net;
        if ($vat <= 0) {
            return $net;
        }

        return $net * ($vat / 100 + 1);
    }

    /**
     * @param $gross
     * @param $vat
     *
     * @return float
     * @deprecated since 5.2 use Money::net() instead.
     */
    //phpcs:ignore
    public static function AsNet($gross, $vat): float // @phpstan-ignore-line
    {
        return self::net($gross, $vat);
    }

    /**
     * @param numeric|numeric-string $gross
     * @param numeric|numeric-string $vat
     *
     * @return float
     */
    public static function net(float|int|string $gross, float|int|string $vat): float
    {
        $vat   = (float)$vat;
        $gross = (float)$gross;
        if ($vat <= 0) {
            return $gross;
        }

        return $gross / ($vat / 100 + 1);
    }
}

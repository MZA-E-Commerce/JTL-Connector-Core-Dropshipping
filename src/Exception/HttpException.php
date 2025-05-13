<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class HttpException extends \Exception
{
    public const UNKNOWN_METHOD = 10;

    /**
     * @param string $method
     *
     * @return self
     */
    public static function unknownMethod(string $method): HttpException
    {
        return new self("Unknown method ({$method})", self::UNKNOWN_METHOD);
    }
}

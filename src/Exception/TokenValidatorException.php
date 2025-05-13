<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class TokenValidatorException extends \Exception
{
    public const EMPTY_TOKEN = 10;

    /**
     * @return self
     */
    public static function emptyToken(): self
    {
        return new self('Connector token cannot be empty', self::EMPTY_TOKEN);
    }
}

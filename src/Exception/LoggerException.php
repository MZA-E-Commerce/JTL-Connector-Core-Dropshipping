<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class LoggerException extends \Exception
{
    public const FORMATTER_NOT_EXISTS = 10;

    /**
     * @param string $className
     *
     * @return self
     */
    public static function formatterNotExists(string $className): self
    {
        return new self(\sprintf('Formatter class "%s" does not exist', $className), self::FORMATTER_NOT_EXISTS);
    }
}

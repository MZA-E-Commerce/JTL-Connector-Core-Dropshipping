<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class MethodDoesNotExistException extends \RuntimeException
{
    /**
     * @param string $methodName
     *
     * @return self
     */
    public static function fromName(string $methodName): self
    {
        return new self(\sprintf('Method "%s" does not exist', $methodName));
    }
}

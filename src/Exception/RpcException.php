<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

use Jtl\Connector\Core\Definition\ErrorCode;

class RpcException extends \Exception
{
    /**
     * @return self
     */
    public static function parseError(): self
    {
        return new self('Parse error', ErrorCode::PARSE_ERROR);
    }

    /**
     * @return self
     */
    public static function invalidRequest(): self
    {
        return new self('Invalid request', ErrorCode::INVALID_REQUEST);
    }

    /**
     * @param string $method
     *
     * @return self
     */
    public static function invalidMethod(string $method): self
    {
        return new self(\sprintf('Invalid method (%s)', $method), ErrorCode::INVALID_METHOD);
    }

    /**
     * @return self
     */
    public static function unpreparedResponse(): self
    {
        return new self('Response is not prepared', ErrorCode::SERVER_ERROR);
    }
}

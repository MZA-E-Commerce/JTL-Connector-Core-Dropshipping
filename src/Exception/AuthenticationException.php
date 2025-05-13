<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

use Jtl\Connector\Core\Definition\ErrorCode;

class AuthenticationException extends \Exception
{
    /**
     * @return self
     */
    public static function failed(): self
    {
        return new self('Authentication failed', ErrorCode::AUTHENTICATION_FAILED);
    }

    /**
     * @return self
     */
    public static function tokenMissing(): self
    {
        return new self('Token is missing', ErrorCode::AUTHENTICATION_FAILED);
    }
}

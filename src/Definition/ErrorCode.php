<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

final class ErrorCode
{
    public const
        NO_SESSION            = 789,
        AUTHENTICATION_FAILED = 790,
        INVALID_SESSION       = -32000,
        UNINITIALIZED_SESSION = -32001,
        INVALID_REQUEST       = -32600,
        PARSE_ERROR           = -32700,
        INVALID_METHOD        = -32701,
        UNKNOWN_CONTROLLER    = 33000,
        UNKNOWN_ACTION        = 33001,
        REQUEST_ERROR         = 400,
        SERVER_ERROR          = 500;
}

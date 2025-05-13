<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Stub\Logger;

use Monolog\Logger as MonoLogger;

/**
 * Class LoggerStub
 *
 * @package Jtl\Connector\Core\Test\Stub\Logger
 */
class LoggerStub
{
    public const
        CHANNEL_CHECKSUM = 'checksum',
        CHANNEL_GLOBAL   = 'global',
        CHANNEL_LINKER   = 'linker',
        CHANNEL_RPC      = 'rpc',
        CHANNEL_SESSION  = 'session';

    public const
        INFO    = 'info',
        WARNING = 'warning',
        DEBUG   = 'debug',
        ERROR   = 'error';

    /** @var array<string, int> */
    protected static array $logLevelMappings = [
        self::INFO    => MonoLogger::INFO,
        self::WARNING => MonoLogger::WARNING,
        self::ERROR   => MonoLogger::ERROR,
        self::DEBUG   => MonoLogger::DEBUG,
    ];
}

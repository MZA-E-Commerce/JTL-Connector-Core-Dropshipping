<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

final class Action
{
    public const
        PULL      = 'pull',
        PUSH      = 'push',
        DELETE    = 'delete',
        STATISTIC = 'statistic',
        AUTH      = 'auth',
        ACK       = 'ack',
        CLEAR     = 'clear',
        FEATURES  = 'features',
        FINISH    = 'finish',
        IDENTIFY  = 'identify',
        INIT      = 'init';

    /** @var string[]|null */
    protected static ?array $actions = null;

    /** @var string[] */
    protected static array $coreActions = [
        self::AUTH,
        self::ACK,
        self::CLEAR,
        self::FEATURES,
        self::FINISH,
        self::IDENTIFY,
        self::INIT,
    ];

    /**
     * @param string $actionName
     *
     * @return bool
     */
    public static function isAction(string $actionName): bool
    {
        return \in_array($actionName, self::getActions(), true);
    }

    /**
     * @return array<string, string>
     */
    public static function getActions(): array
    {
        if (\is_null(self::$actions)) {
            /** @var array<string, string> $actions */
            $actions       = (new \ReflectionClass(self::class))->getConstants();
            self::$actions = $actions;
        }

        return self::$actions;
    }

    /**
     * @param string $actionName
     *
     * @return bool
     */
    public static function isCoreAction(string $actionName): bool
    {
        return \in_array($actionName, self::$coreActions, true);
    }
}

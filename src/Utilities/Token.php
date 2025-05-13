<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Utilities;

/**
 * Class Token
 *
 * @package Jtl\Connector\Core\Utilities
 */
class Token
{
    /**
     * Generate UUIDv4
     *
     * @see https://github.com/Fleshgrinder/php-uuid/blob/php-7.1/src/UUID.php
     * @return string
     * @throws \Exception
     */
    public static function generate(): string
    {
        $bytes = \random_bytes(16);

        $bytes[6] = \chr((\ord($bytes[6]) & 0b00001111) | 0b01000000);
        $bytes[8] = \chr((\ord($bytes[8]) & 0b00111111) | 0b10000000);

        $hex = \bin2hex($bytes);

        $format = \substr($hex, 0, 8) . '-' .
                  \substr($hex, 8, 4) . '-' .
                  \substr($hex, 12, 4) . '-' .
                  \substr($hex, 16, 4) . '-' .
                  \substr($hex, 20);

        return \strtoupper($format);
    }
}

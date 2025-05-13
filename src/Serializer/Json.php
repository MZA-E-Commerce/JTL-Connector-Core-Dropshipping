<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer;

use Jtl\Connector\Core\Exception\JsonException;

/**
 * Yaml Class
 *
 * @access public
 */
class Json
{
    /**
     * @param mixed $object
     * @param bool  $pretty
     *
     * @return false|string
     * @throws JsonException
     * @throws \JsonException
     */
    public static function encode(mixed $object, bool $pretty = false): bool|string
    {
        $options = 0;
        if ($pretty) {
            $options = \JSON_PRETTY_PRINT;
        }

        $json = \json_encode($object, \JSON_THROW_ON_ERROR | $options);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw JsonException::encoding(\json_last_error_msg());
        }

        return $json;
    }

    /**
     * @param string $string
     * @param bool   $assoc
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws JsonException
     */
    public static function decode(string $string, bool $assoc = false): mixed
    {
        if ($string === '') {
            throw new \InvalidArgumentException(\sprintf('Invalid parameter "%s"', \var_export($string, true)));
        }

        $object = \json_decode($string, $assoc, 512, \JSON_THROW_ON_ERROR);
        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw JsonException::decoding(\json_last_error_msg(), $string);
        }

        return $object;
    }
}

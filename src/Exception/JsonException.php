<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class JsonException extends \RuntimeException
{
    public const ENCODING_ERROR = 10;
    public const DECODING_ERROR = 20;

    /**
     * @param string $lastErrorMessage
     *
     * @return self
     */
    public static function encoding(string $lastErrorMessage): self
    {
        return new self(\sprintf('Error while encoding into JSON: %s', $lastErrorMessage), static::ENCODING_ERROR);
    }

    /**
     * @param string      $lastErrorMessage
     * @param string|null $jsonString
     *
     * @return self
     */
    public static function decoding(string $lastErrorMessage, ?string $jsonString = null): self
    {
        $msg = \sprintf('Error while decoding JSON: %s', $lastErrorMessage);
        if (!\is_null($jsonString)) {
            $msg = \sprintf('Error while decoding JSON: %s' . \PHP_EOL . 'String: %s', $lastErrorMessage, $jsonString);
        }
        return new self($msg, static::DECODING_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class TranslatableAttributeException extends \Exception
{
    public const
        VALUE_TYPE_INVALID    = 20,
        DECODING_VALUE_FAILED = 30;

    /**
     * @param string $attributeName
     * @param string $type
     *
     * @return self
     */
    public static function valueTypeInvalid(string $attributeName, string $type): self
    {
        return new self(
            \sprintf('[%s] "%s" is not a valid translatable attribute value type', $attributeName, $type),
            self::VALUE_TYPE_INVALID
        );
    }

    /**
     * @param string $attributeName
     * @param string $errorMessage
     *
     * @return self
     */
    public static function decodingValueFailed(string $attributeName, string $errorMessage): self
    {
        return new self(
            \sprintf('[%s] Decoding translatable attribute value failed: %s', $attributeName, $errorMessage),
            self::DECODING_VALUE_FAILED
        );
    }
}

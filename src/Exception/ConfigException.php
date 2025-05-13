<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Exception;

class ConfigException extends \Exception
{
    public const
        EMPTY_KEY                = 10,
        UNKNOWN_TYPE             = 20,
        WRONG_TYPE               = 30,
        UNKNOWN_PARAMETER        = 40,
        SCHEMA_VALIDATION_ERRORS = 50;

    /**
     * @return self
     */
    public static function keyIsEmpty(): self
    {
        return new self('Key must contain at least one character', self::EMPTY_KEY);
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public static function unknownType(string $type): self
    {
        return new self(\sprintf('Parameter data type (%s) does not exist', $type), self::UNKNOWN_TYPE);
    }

    /**
     * @param string $expectedType
     * @param string $givenType
     *
     * @return self
     */
    public static function wrongType(string $expectedType, string $givenType): self
    {
        return new self(
            \sprintf(
                'Wrong parameter data type. %s was expected but %s is given',
                $expectedType,
                $givenType
            ),
            self::WRONG_TYPE
        );
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public static function unknownParameter(string $key): self
    {
        return new self(\sprintf('Parameter (%s) does not exist', $key), self::UNKNOWN_PARAMETER);
    }

    /**
     * @param array<scalar> $invalidProperties
     * @param array<scalar> $missingProperties
     *
     * @return self
     */
    public static function configValidationErrors(array $invalidProperties, array $missingProperties): self
    {
        $messageParts = ['Configuration could not get validated.'];

        $invalidPropertiesCount = \count($invalidProperties);
        if ($invalidPropertiesCount > 0) {
            $message = $invalidPropertiesCount === 1
                ? 'The property "%s" has an invalid value.'
                : 'The properties "%s" have invalid values.';

            $messageParts[] = \sprintf($message, \implode(', ', $invalidProperties));
        }

        $missingPropertiesCount = \count($missingProperties);
        if ($missingPropertiesCount > 0) {
            $message = $missingPropertiesCount === 1
                ? 'The required property "%s" is missing.'
                : 'The required properties "%s" are missing.';

            $messageParts[] = \sprintf($message, \implode(', ', $missingProperties));
        }

        return new self(\implode(' ', $messageParts), self::SCHEMA_VALIDATION_ERRORS);
    }
}

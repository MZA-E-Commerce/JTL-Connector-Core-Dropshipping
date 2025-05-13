<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Utilities\Validator;

use Jtl\Connector\Core\Exception\ArrayKeyDoesNotExistException;
use Jtl\Connector\Core\Exception\MethodDoesNotExistException;
use PHPStan\BetterReflection\Reflection\Exception\PropertyDoesNotExist;

class Validator implements ValidatorInterface
{
    private mixed  $value;
    private string $assertedType;

    /**
     * @inheritDoc
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function string(): string
    {
        if (!\is_string($this->value)) {
            $this->assertedType = 'string';
            $this->throwException();
        }

        /** @var string $value */
        $value = $this->value;

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function throwException(): void
    {
        $value   = \is_object($this->value) ? \get_class($this->value) : $this->value;
        $message = $this->hasValue()
            ? $value . ' must be type ' . $this->assertedType
            : 'Type Error: Value is null';

        throw new \TypeError($message);
    }

    /**
     * @inheritDoc
     */
    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function int(): int
    {
        if (!\is_int($this->value)) {
            $this->assertedType = 'int';
            $this->throwException();
        }

        /** @var int $value */
        $value = $this->value;

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function float(): float
    {
        if (!\is_float($this->value)) {
            $this->assertedType = 'float';
            $this->throwException();
        }

        /** @var float $value */
        $value = $this->value;

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function bool(): bool
    {
        if (!\is_bool($this->value)) {
            $this->assertedType = 'bool';
            $this->throwException();
        }

        /** @var bool $value */
        $value = $this->value;

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function instanceOf(string $class): bool
    {
        if (!($this->value instanceof $class)) {
            $this->assertedType = $class;
            $this->throwException();
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws PropertyDoesNotExist
     * @throws \TypeError
     */
    public function hasProperty(string $propertyName): bool
    {
        $this->isObject();
        if (!isset($this->value->$propertyName)) {
            throw PropertyDoesNotExist::fromName($propertyName);
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function isObject(): bool
    {
        if (!\is_object($this->value)) {
            $this->assertedType = 'object';
            $this->throwException();
        }

        return true;
    }

    /**
     * @param int|string $keyName
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \TypeError
     * @throws ArrayKeyDoesNotExistException
     */
    public function hasKey(int|string $keyName): bool
    {
        $value = $this->array();
        $this->isValidArrayKeyName($keyName);
        if (!\array_key_exists($keyName, $value)) {
            throw ArrayKeyDoesNotExistException::fromKey($keyName);
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function array(): array
    {
        if (!\is_array($this->value)) {
            $this->assertedType = 'array';
            $this->throwException();
        }

        /** @var array<mixed> $value */
        $value = $this->value;

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \InvalidArgumentException
     */
    public function isValidArrayKeyName(mixed $keyName): bool
    {
        if (!((\is_string($keyName) && $keyName !== '') || (\is_int($keyName) && $keyName >= 0))) {
            throw new \InvalidArgumentException('$keyName must be a string or int equals 0 or greater.');
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws MethodDoesNotExistException
     * @throws \TypeError
     */
    public function hasMethod(string $methodName): bool
    {
        $this->isObject();
        /** @var object $value */
        $value = $this->value;
        if (!\method_exists($value, $methodName)) {
            throw MethodDoesNotExistException::fromName($methodName);
        }

        return true;
    }
}

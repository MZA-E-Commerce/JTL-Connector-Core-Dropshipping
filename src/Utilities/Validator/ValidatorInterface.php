<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Utilities\Validator;

use Jtl\Connector\Core\Exception\ArrayKeyDoesNotExistException;
use Jtl\Connector\Core\Exception\MethodDoesNotExistException;
use PHPStan\BetterReflection\Reflection\Exception\PropertyDoesNotExist;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     */
    public function __construct(mixed $value);

    /**
     * @return bool
     */
    public function hasValue(): bool;

    /**
     * @return string
     * @throws \TypeError
     */
    public function string(): string;

    /**
     * @return int
     * @throws \TypeError
     */
    public function int(): int;

    /**
     * @return float
     * @throws \TypeError
     */
    public function float(): float;

    /**
     * @return bool
     * @throws \TypeError
     */
    public function bool(): bool;

    /**
     * @return array<mixed>
     * @throws \TypeError
     */
    public function array(): array;

    /**
     * @param string $class
     *
     * @return bool
     * @throws \TypeError
     */
    public function instanceOf(string $class): bool;

    /**
     * @return bool
     * @throws \TypeError
     */
    public function isObject(): bool;

    /**
     * @return void
     * @throws \TypeError
     */
    public function throwException(): void;

    /**
     * @param string $propertyName
     *
     * @return bool
     * @throws PropertyDoesNotExist
     * @throws \TypeError
     */
    public function hasProperty(string $propertyName): bool;

    /**
     * @param int|string $keyName
     *
     * @return bool
     * @throws ArrayKeyDoesNotExistException
     * @throws \TypeError
     * @throws \InvalidArgumentException
     */
    public function hasKey(int|string $keyName): bool;

    /**
     * @param mixed $keyName
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValidArrayKeyName(mixed $keyName): bool;

    /**
     * @param string $methodName
     *
     * @return bool
     * @throws MethodDoesNotExistException
     * @throws \TypeError
     */
    public function hasMethod(string $methodName): bool;
}

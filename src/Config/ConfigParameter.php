<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Config;

use Jtl\Connector\Core\Exception\ConfigException;

class ConfigParameter
{
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DOUBLE  = 'double';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_STRING  = 'string';

    /** @var string[] */
    protected static array $types = [
        self::TYPE_BOOLEAN,
        self::TYPE_DOUBLE,
        self::TYPE_INTEGER,
        self::TYPE_STRING,
    ];
    protected string       $key;
    protected string       $type;
    protected bool         $required = true;
    protected bool         $global   = false;
    protected mixed        $defaultValue;

    /**
     * ConfigParameter constructor.
     *
     * @param string $key
     * @param string $type
     * @param bool   $required
     * @param bool   $global
     *
     * @throws ConfigException
     */
    public function __construct(string $key, string $type, bool $required = true, bool $global = false)
    {
        $this->setKey($key);
        $this->setType($type);
        $this->required = $required;
        $this->global   = $global;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isType(string $type): bool
    {
        return \in_array($type, self::$types, true);
    }

    /**
     * @param string     $key
     * @param string     $type
     * @param bool       $required
     * @param bool       $global
     * @param mixed|null $defaultValue
     *
     * @return self
     * @throws ConfigException
     */
    public static function create(
        string $key,
        string $type = self::TYPE_STRING,
        bool   $required = true,
        bool   $global = false,
        mixed  $defaultValue = null
    ): self {
        return (new self($key, $type, $required, $global))->setDefaultValue($defaultValue);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return $this
     * @throws ConfigException
     */
    protected function setKey(string $key): self
    {
        if (empty($key)) {
            throw ConfigException::keyIsEmpty();
        }

        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     * @throws ConfigException
     */
    protected function setType(string $type): self
    {
        if (!self::isType($type)) {
            throw ConfigException::unknownType($type);
        }
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->global;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return isset($this->defaultValue);
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     *
     * @return $this
     * @throws ConfigException
     */
    public function setDefaultValue(mixed $defaultValue): self
    {
        if (!\is_null($defaultValue) && !$this->isValidValue($defaultValue)) {
            throw ConfigException::wrongType($this->getType(), \gettype($defaultValue));
        }
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValidValue(mixed $value): bool
    {
        return \gettype($value) === $this->getType();
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Config;

use Jtl\Connector\Core\Exception\ConfigException;
use Noodlehaus\Config;
use Noodlehaus\Exception\EmptyDirectoryException;
use Noodlehaus\Parser\Json;

class FileConfig extends Config implements CoreConfigInterface, ConfigSchemaConfigInterface
{
    protected string $filePath = '';

    protected ConfigSchema $configSchema;

    /**
     * FileConfig constructor.
     *
     * @param string            $filePath
     * @param ConfigSchema|null $configSchema
     *
     * @throws EmptyDirectoryException
     */
    public function __construct(string $filePath, ?ConfigSchema $configSchema = null)
    {
        if (!\is_file($filePath) && \is_dir(\dirname($filePath))) {
            parent::__construct('{}', new Json(), true);
        } else {
            parent::__construct($filePath);
        }

        $this->configSchema = $configSchema ?? new ConfigSchema();
        $this->filePath     = $filePath;
    }

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): ConfigSchema
    {
        return $this->configSchema;
    }

    /**
     * @inheritDoc
     */
    public function setConfigSchema(ConfigSchema $configSchema): self
    {
        $this->configSchema = $configSchema;

        return $this;
    }

    /**
     * @phpstan-param string $key
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     * @throws ConfigException
     */
    public function set(mixed $key, mixed $value): void
    {
        if (empty($key)) {
            throw ConfigException::keyIsEmpty();
        }

        parent::set($key, $value);
    }

    /**
     * @return void
     */
    public function write(): void
    {
        $this->toFile($this->filePath);
    }

    /**
     * @param string $valueName
     * @param mixed  $default
     *
     * @return bool
     * @throws ConfigException
     * @throws \TypeError
     */
    private function check(string $valueName, mixed $default): bool
    {
        if (!\is_scalar($default) && !\is_null($default)) {
            self::throwTypeError(
                \sprintf('Default value for %s', $valueName),
                'scalar'
            );
        }
        $value = $this->get($valueName, $default);

        return $this->configSchema->getParameter($valueName)->isValidValue($value);
    }

    /**
     * @param string $valueName
     * @param string $type
     *
     * @return void
     * @throws \TypeError
     */
    private static function throwTypeError(string $valueName, string $type): void
    {
        throw new \TypeError($valueName . ' is not valid type ' . $type . '.');
    }

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function getString(string $valueName, ?string $default = null): ?string
    {
        $value = $this->get($valueName, $default);
        if (empty($value)) {
            return '';
        }
        if ($value === $default) {
            return $default;
        }
        if ($this->check($valueName, $default) === false) {
            self::throwTypeError($valueName, ConfigParameter::TYPE_STRING);
        }
        if (!\is_scalar($value)) {
            throw new \RuntimeException('getString must return a scalar type!');
        }

        return (string)$value;
    }

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function getBool(string $valueName, ?bool $default = null): ?bool
    {
        $value = $this->get($valueName, $default);
        if (empty($value)) {
            return null;
        }
        if ($value === $default) {
            return $default;
        }
        if ($this->check($valueName, $default) === false) {
            self::throwTypeError($valueName, ConfigParameter::TYPE_BOOLEAN);
        }

        if (!\is_scalar($value)) {
            throw new \RuntimeException('getBool must have a scalar value!');
        }

        return (bool)$value;
    }

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function getInt(string $valueName, ?int $default = null): ?int
    {
        $value = $this->get($valueName, $default);
        if (empty($value)) {
            return null;
        }
        if ($value === $default) {
            return $default;
        }
        if ($this->has($valueName) && $this->check($valueName, $default) === false) {
            self::throwTypeError($valueName, ConfigParameter::TYPE_INTEGER);
        }
        if (!\is_numeric($value)) {
            throw new \RuntimeException('getInt must return a numeric type!');
        }

        return (int)$value;
    }

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function getFloat(string $valueName, ?float $default = null): ?float
    {
        $value = $this->get($valueName, $default);
        if (empty($value)) {
            return null;
        }
        if ($value === $default) {
            return $default;
        }
        if ($this->has($valueName) && $this->check($valueName, $default) === false) {
            self::throwTypeError($valueName, ConfigParameter::TYPE_DOUBLE);
        }

        if (!\is_numeric($value)) {
            throw new \RuntimeException('getFloat must return a numeric type!');
        }

        return (float)$value;
    }
}

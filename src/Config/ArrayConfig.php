<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Config;

use Jtl\Connector\Core\Exception\ConfigException;
use Noodlehaus\AbstractConfig;

class ArrayConfig extends AbstractConfig implements CoreConfigInterface
{
    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function getBool(string $valueName, ?bool $default = null): bool
    {
        $value = $this->get($valueName, $default);
        if (!\is_bool($value)) {
            throw new \TypeError(\sprintf('Value for key "%s" must be of type bool', $valueName));
        }

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function getString(string $valueName, ?string $default = null): string
    {
        $value = $this->get($valueName, $default);
        if (!\is_string($value)) {
            throw new \TypeError(\sprintf('Value for key "%s" must be of type string', $valueName));
        }

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function getInt(string $valueName, ?int $default = null): int
    {
        $value = $this->get($valueName, $default);
        if (!\is_int($value)) {
            throw new \TypeError(\sprintf('Value for key "%s" must be of type int', $valueName));
        }

        return $value;
    }

    /**
     * @inheritDoc
     * @throws \TypeError
     */
    public function getFloat(string $valueName, ?float $default = null): float
    {
        $value = $this->get($valueName, $default);
        if (!\is_float($value)) {
            throw new \TypeError(\sprintf('Value for key "%s" must be of type float', $valueName));
        }

        return $value;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Config;

use Jtl\Connector\Core\Exception\ConfigException;
use Noodlehaus\ConfigInterface;

interface CoreConfigInterface extends ConfigInterface
{
    /**
     * @param string    $valueName
     * @param bool|null $default
     *
     * @return bool|null
     * @throws \TypeError
     * @throws ConfigException
     */
    public function getBool(string $valueName, ?bool $default = null): ?bool;

    /**
     * @param string      $valueName
     * @param string|null $default
     *
     * @return string|null
     * @throws ConfigException
     * @throws \TypeError
     */
    public function getString(string $valueName, ?string $default = null): ?string;

    /**
     * @param string   $valueName
     * @param int|null $default
     *
     * @return int|null
     * @throws ConfigException
     * @throws \TypeError
     */
    public function getInt(string $valueName, ?int $default = null): ?int;

    /**
     * @param string     $valueName
     * @param float|null $default
     *
     * @return float|null
     * @throws ConfigException
     * @throws \TypeError
     */
    public function getFloat(string $valueName, ?float $default = null): ?float;
}

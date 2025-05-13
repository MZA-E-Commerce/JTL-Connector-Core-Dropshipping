<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Checksum;

interface ChecksumLoaderInterface
{
    /**
     * Loads the checksum
     *
     * @param string $endpointId
     * @param int    $type
     *
     * @return string
     */
    public function read(string $endpointId, int $type): string;

    /**
     * Loads the checksum
     *
     * @param string $endpointId
     * @param int    $type
     * @param string $checksum
     *
     * @return bool
     */
    public function write(string $endpointId, int $type, string $checksum): bool;

    /**
     * Loads the checksum
     *
     * @param string $endpointId
     * @param int    $type
     *
     * @return bool
     */
    public function delete(string $endpointId, int $type): bool;
}

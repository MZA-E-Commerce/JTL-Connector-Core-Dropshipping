<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Mapper;

interface PrimaryKeyMapperInterface
{
    /**
     * Host ID getter
     *
     * @param int    $type
     * @param string $endpointId
     *
     * @return int|null
     */
    public function getHostId(int $type, string $endpointId): ?int;

    /**
     * Endpoint ID getter
     *
     * @param int $type
     * @param int $hostId
     *
     * @return string|null
     */
    public function getEndpointId(int $type, int $hostId): ?string;

    /**
     * Save link to database
     *
     * @param int    $type
     * @param string $endpointId
     * @param int    $hostId
     *
     * @return bool
     */
    public function save(int $type, string $endpointId, int $hostId): bool;

    /**
     * Delete link from database
     *
     * @param int         $type
     * @param string|null $endpointId
     * @param int|null    $hostId
     *
     * @return bool
     */
    public function delete(int $type, ?string $endpointId = null, ?int $hostId = null): bool;

    /**
     * Clears the entire link table
     *
     * @param int|null $type
     *
     * @return bool
     */
    public function clear(?int $type = null): bool;
}

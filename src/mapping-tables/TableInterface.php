<?php

declare(strict_types=1);

namespace Jtl\Connector\MappingTables;

interface TableInterface
{
    /**
     * @return int[]
     */
    public function getTypes(): array;

    /**
     * @param string $endpoint
     *
     * @return int|null
     */
    public function getHostId(string $endpoint): ?int;

    /**
     * @param int      $hostId
     * @param int|null $type
     *
     * @return string|null
     */
    public function getEndpoint(int $hostId, ?int $type = null): ?string;

    /**
     * @param string $endpoint
     * @param int    $hostId
     *
     * @return int
     */
    public function save(string $endpoint, int $hostId): int;

    /**
     * @param string|null $endpoint
     * @param int|null    $hostId
     * @param int|null    $type
     *
     * @return int
     */
    public function remove(?string $endpoint = null, ?int $hostId = null, ?int $type = null): int;

    /**
     * @param int|null $type
     *
     * @return int
     */
    public function clear(?int $type = null): int;

    /**
     * @param string[] $where
     * @param mixed[]  $parameters
     * @param string[] $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param int|null $type
     *
     * @return int
     */
    public function count(
        array $where = [],
        array $parameters = [],
        array $orderBy = [],
        ?int   $limit = null,
        ?int   $offset = null,
        ?int   $type = null
    ): int;

    /**
     * @param string[] $where
     * @param mixed[]  $parameters
     * @param string[] $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param int|null $type
     *
     * @return string[]
     */
    public function findEndpoints(
        array $where = [],
        array $parameters = [],
        array $orderBy = [],
        ?int   $limit = null,
        ?int   $offset = null,
        ?int   $type = null
    ): array;

    /**
     * @param string[] $endpoints
     *
     * @return string[]
     */
    public function filterMappedEndpoints(array $endpoints): array;
}

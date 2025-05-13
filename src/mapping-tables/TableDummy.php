<?php

declare(strict_types=1);

namespace Jtl\Connector\MappingTables;

class TableDummy implements TableInterface
{
    /** @var int[] */
    protected array $types = [];

    /**
     * @return int[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param int $type
     *
     * @return void
     */
    public function setType(int $type): void
    {
        if (!\in_array($type, $this->types, true)) {
            $this->types[] = $type;
        }
    }

    /**
     * @param string $endpoint
     *
     * @return int|null
     */
    public function getHostId(string $endpoint): ?int
    {
        return null;
    }

    /**
     * @param int      $hostId
     * @param int|null $type
     *
     * @return string|null
     */
    public function getEndpoint(int $hostId, ?int $type = null): ?string
    {
        return null;
    }

    /**
     * @param string $endpoint
     * @param int    $hostId
     *
     * @return int
     */
    public function save(string $endpoint, int $hostId): int
    {
        return 0;
    }

    /**
     * @param string|null $endpoint
     * @param int|null    $hostId
     * @param int|null    $type
     *
     * @return int
     */
    public function remove(?string $endpoint = null, ?int $hostId = null, ?int $type = null): int
    {
        return 0;
    }

    /**
     * @param int|null $type
     *
     * @return int
     */
    public function clear(?int $type = null): int
    {
        return 0;
    }

    /**
     * @param string[] $where
     * @param string[] $parameters
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
        ?int  $limit = null,
        ?int  $offset = null,
        ?int  $type = null
    ): int {
        return 0;
    }

    /**
     * @param string[] $where
     * @param string[] $parameters
     * @param string[] $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param int|null $type
     *
     * @return array<empty>
     */
    public function findEndpoints(
        array $where = [],
        array $parameters = [],
        array $orderBy = [],
        ?int  $limit = null,
        ?int  $offset = null,
        ?int  $type = null
    ): array {
        return [];
    }

    /**
     * @param array|string[] $endpoints
     *
     * @return array|string[]
     */
    public function filterMappedEndpoints(array $endpoints): array
    {
        return $endpoints;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Database Query Filter
 *
 * @access  public
 * @package Jtl\Connector\Core\Model
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class QueryFilter
{
    public const
        FILTER_FETCH_CHILDREN = 'fetchChildren',
        FILTER_PARENT_ID      = 'parentId',
        FILTER_RELATION_TYPE  = 'relationType';

    /** Query item count limitation */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('limit')]
    protected int $limit = 100;

    /**
     * Query item filter (where)
     *
     * @var array<string, string>
     */
    #[Serializer\Type('array<string, string>')]
    #[Serializer\SerializedName('filters')]
    protected array $filters = [];

    /**
     * Constructor
     *
     * @param int $limit
     */
    public function __construct(int $limit = 100)
    {
        $this->filters = [];
        $this->limit   = $limit;
    }

    /**
     * Limit Getter
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Limit Setter
     *
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Filters Getter
     *
     * @return string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Filters Setter
     *
     * @param array<string, mixed> $filters
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setFilters(array $filters): self
    {
        foreach ($filters as $index => $filter) {
            if (\is_scalar($filter)) {
                $filters[$index] = (string)$filter;
            } else {
                throw new \InvalidArgumentException('Filter-Array must be type array<string, scalar>.');
            }
        }
        /** @var array<string, string> $filters */
        $this->filters = $filters;

        return $this;
    }

    /**
     * Add one Filter
     *
     * @param string $key   Filter key
     * @param string $value Filter value
     *
     * @return $this
     */
    public function addFilter(string $key, string $value): self
    {
        $this->filters[$key] = $value;

        return $this;
    }

    /**
     * Delete one Filter
     *
     * @param string $key
     *
     * @return bool
     */
    public function deleteFilter(string $key): bool
    {
        if ($this->isFilter($key)) {
            unset($this->filters[$key]);

            return true;
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isFilter(string $key): bool
    {
        return isset($this->filters[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getFilter(string $key): mixed
    {
        if ($this->isFilter($key)) {
            return $this->filters[$key];
        }

        return null;
    }

    /**
     * @return void
     */
    public function resetFilters(): void
    {
        $this->filters = [];
    }

    /**
     * @param string     $oldKey
     * @param string     $newKey
     * @param mixed|null $value
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function overrideFilter(string $oldKey, string $newKey, mixed $value = null): bool
    {
        if ($this->isFilter($oldKey)) {
            if ($value === null) {
                $value = $this->filters[$oldKey];
            }
            if (\is_scalar($value)) {
                $value = (string)$value;
            } else {
                throw new \InvalidArgumentException('$value must be scalar!');
            }

            unset($this->filters[$oldKey]);
            $this->filters[$newKey] = $value;

            return true;
        }

        return false;
    }

    /**
     * Setter
     *
     * @param \stdClass $obj
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function set(\stdClass $obj): void
    {
        if (!\is_object($obj)) {
            return;
        }

        if (isset($obj->limit)) {
            $this->setLimit($obj->limit);
        }

        if (isset($obj->filters) && \is_object($obj->filters)) {
            $this->setFilters(\get_object_vars($obj->filters));
        }
    }
}

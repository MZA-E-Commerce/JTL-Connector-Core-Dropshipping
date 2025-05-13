<?php

declare(strict_types=1);

namespace Jtl\Connector\MappingTables;

class TableCollection
{
    protected bool       $strictMode = false;
    protected TableDummy $tableDummy;

    /** @var array<TableInterface> */
    protected array $tables = [];

    /**
     * @param TableInterface ...$tables
     */
    public function __construct(TableInterface ...$tables)
    {
        foreach ($tables as $table) {
            $this->set($table);
        }
    }

    /**
     * @param TableInterface $table
     *
     * @return $this
     */
    public function set(TableInterface $table): self
    {
        foreach ($table->getTypes() as $type) {
            $this->tables[$type] = $table;
        }

        return $this;
    }

    /**
     * @param TableInterface $table
     *
     * @return void
     */
    public function removeByInstance(TableInterface $table): void
    {
        $this->tables = \array_filter($this->tables, function (TableInterface $collectedTable) use ($table) {
            return $collectedTable !== $table;
        });
    }

    /**
     * @param int $type
     *
     * @return void
     */
    public function removeByType(int $type): void
    {
        if ($this->has($type)) {
            unset($this->tables[$type]);
        }
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function has(int $type): bool
    {
        return isset($this->tables[$type]);
    }

    /**
     * @param int $type
     *
     * @return TableInterface
     * @throws MappingTablesException
     */
    public function get(int $type): TableInterface
    {
        if ($this->has($type)) {
            return $this->tables[$type];
        }

        if (!$this->strictMode) {
            return $this->getTableDummy($type);
        }

        throw MappingTablesException::tableForTypeNotFound($type);
    }

    /**
     * @return array<TableInterface>
     */
    public function toArray(): array
    {
        $tables = [];
        foreach ($this->tables as $table) {
            if (!\in_array($table, $tables, true)) {
                $tables[] = $table;
            }
        }

        return $tables;
    }

    /**
     * @return bool
     */
    public function isStrictMode(): bool
    {
        return $this->strictMode;
    }

    /**
     * @param bool $strictMode
     *
     * @return $this
     */
    public function setStrictMode(bool $strictMode): self
    {
        $this->strictMode = $strictMode;

        return $this;
    }

    /**
     * @param int $type
     *
     * @return TableDummy
     */
    protected function getTableDummy(int $type): TableDummy
    {
        if (!isset($this->tableDummy)) {
            $this->tableDummy = new TableDummy();
        }

        $this->tableDummy->setType($type);

        return $this->tableDummy;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Dbc\Schema;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;

class TableRestriction
{
    protected Table $table;
    protected string $columnName;
    protected mixed $value;

    /**
     * TableRestriction constructor.
     *
     * @param Table  $table
     * @param string $columnName
     * @param mixed  $columnValue
     *
     * @throws SchemaException
     */
    public function __construct(Table $table, string $columnName, mixed $columnValue)
    {
        if (!$table->hasColumn($columnName)) {
            throw SchemaException::columnDoesNotExist($columnName, $table->getName());
        }

        $this->table      = $table;
        $this->columnName = $columnName;
        $this->value      = $columnValue;
    }

    /**
     * @param Table  $table
     * @param string $columnName
     * @param mixed  $columnValue
     *
     * @return self
     * @throws SchemaException
     */
    public static function create(Table $table, string $columnName, mixed $columnValue): self
    {
        return new self($table, $columnName, $columnValue);
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return mixed
     */
    public function getColumnValue(): mixed
    {
        return $this->value;
    }
}

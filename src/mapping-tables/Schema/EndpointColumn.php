<?php

declare(strict_types=1);

namespace Jtl\Connector\MappingTables\Schema;

use Doctrine\DBAL\Schema\Column;

class EndpointColumn
{
    protected Column $column;

    protected bool $primary = true;

    /**
     * EndpointColumn constructor.
     *
     * @param Column $column
     * @param bool   $primary
     */
    public function __construct(Column $column, bool $primary = true)
    {
        $this->column  = $column;
        $this->primary = $primary;
    }

    /**
     * @return Column
     */
    public function getColumn(): Column
    {
        return $this->column;
    }

    /**
     * @return bool
     */
    public function primary(): bool
    {
        return $this->primary;
    }

    /**
     * @param Column $column
     * @param bool   $primary
     * @return self
     */
    public static function create(Column $column, bool $primary = true): self
    {
        return new self($column, $primary);
    }
}

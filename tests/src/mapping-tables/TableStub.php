<?php

declare(strict_types=1);

namespace Jtl\Connector\MappingTables;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use Jtl\Connector\Dbc\DbcRuntimeException;
use Jtl\Connector\Dbc\DbManager;

class TableStub extends AbstractTable implements TableInterface
{
    public const COL_ID1    = 'id1';
    public const COL_ID2    = 'id2';
    public const COL_VAR    = 'strg';
    public const TYPE1      = 815;
    public const TYPE2      = 7;
    public const TABLE_NAME = 'mapping_table';

    /**
     * @param DbManager $dbManager
     *
     * @throws MappingTablesException
     */
    public function __construct(DbManager $dbManager)
    {
        parent::__construct($dbManager, false);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @return void
     * @throws MappingTablesException
     * @throws Exception
     */
    public function defineEndpoint(): void
    {
        $this->setEndpointColumn(self::COL_ID1, Types::INTEGER);
        $this->setEndpointColumn(self::COL_ID2, Types::INTEGER);
        $this->setEndpointColumn(self::COL_VAR, Types::STRING, false);
    }

    /**
     * @param string $columnName
     * @param string $columnType
     * @param bool   $primary
     *
     * @return Column
     * @throws Exception
     * @throws MappingTablesException
     */
    public function setEndpointColumn(string $columnName, string $columnType, bool $primary = true): Column
    {
        return parent::setEndpointColumn($columnName, $columnType, $primary);
    }

    /**
     * @return int[]
     */
    public function getTypes(): array
    {
        return [self::TYPE1, self::TYPE2];
    }

    /**
     * @param Table $tableSchema
     *
     * @return void
     * @throws Exception
     * @throws MappingTablesException
     * @throws SchemaException
     * @throws DbcRuntimeException
     * @throws \RuntimeException
     */
    public function createTableSchema(Table $tableSchema): void
    {
        parent::createTableSchema($tableSchema);
    }

    /**
     * @param string $endpointId
     *
     * @return string[]
     * @throws MappingTablesException
     */
    public function explodeEndpoint(string $endpointId): array
    {
        return parent::explodeEndpoint($endpointId);
    }

    /**
     * @param mixed[] $data
     *
     * @return string
     */
    public function implodeEndpoint(array $data): string
    {
        return parent::implodeEndpoint($data);
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     * @throws DbcRuntimeException
     * @throws MappingTablesException
     * @throws DBALException
     * @throws \RuntimeException
     */
    public function createEndpointData(array $data): array
    {
        return parent::createEndpointData($data);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasEndpointColumn(string $name): bool
    {
        return parent::hasEndpointColumn($name);
    }

    /**
     * @param bool $onlyPrimaryColumns
     *
     * @return Column[]
     */
    public function getEndpointColumns(bool $onlyPrimaryColumns = false): array
    {
        return parent::getEndpointColumns($onlyPrimaryColumns);
    }
}

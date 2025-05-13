<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Database;

/**
 * Database Interface
 */
interface DatabaseInterface
{
    /**
     * Connect to Database
     *
     * @param array<string, mixed>|null $options
     *
     * @return void
     */
    public function connect(?array $options = null): void;

    /**
     * Disconnect from Database
     *
     * @return bool
     */
    public function close(): bool;

    /**
     * Database Query
     *
     * @param string $query
     *
     * @return bool|int|array<int, array<string, mixed>>|null
     */
    public function query(string $query): bool|int|array|null;

    /**
     * Database connection state
     *
     * @return bool $this->_isConnected
     */
    public function isConnected(): bool;

    /**
     * Returns a string that has been properly escaped
     *
     * @param string $query
     *
     * @return string
     */
    public function escapeString(string $query): string;
}

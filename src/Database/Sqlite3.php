<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Database;

use Jtl\Connector\Core\Exception\DatabaseException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Sqlite3 implements DatabaseInterface, LoggerAwareInterface
{
    /**
     * Sqlite 3 sharedcache value
     */
    public const SQLITE3_OPEN_SHAREDCACHE = 0x00020000;
    /**
     * Path to the SQLite database, or :memory: to use in-memory database.
     */
    public string $location;
    /**
     * Optional flags used to determine how to open the SQLite database.
     */
    public int $mode;
    /**
     * Database connection state
     */
    protected bool $isConnected = false;
    /**
     * Sqlite 3 Database object
     */
    protected \SQLite3        $db;
    protected LoggerInterface $logger;

    /**
     * @inheritDoc
     * @throws DatabaseException
     */
    public function connect(?array $options = null): void
    {
        $this->setOptions($options);
        if (isset($this->location) && $this->location === '') {
            throw new DatabaseException('Wrong type or empty location');
        }

        if (!isset($this->mode)) {
            $this->mode = \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE | self::SQLITE3_OPEN_SHAREDCACHE;
        }

        $this->logger = new NullLogger();

        try {
            $this->db = new \SQLite3($this->location, $this->mode);
            $this->db->busyTimeout(2000);
            $this->db->querySingle('PRAGMA journal_mode = wal;');
            $this->db->enableExceptions(true);

            $this->isConnected = true;
        } catch (\Throwable $exc) {
            throw new DatabaseException($exc->getMessage() . ' "' . $this->location . '"');
        }
    }

    /**
     * Set Options
     *
     * @param array<string, mixed>|null $options
     *
     * @return void
     */
    public function setOptions(?array $options = null): void
    {
        if (\is_array($options)) {
            // Location
            if (isset($options['location']) && \is_string($options['location']) && $options['location'] !== '') {
                $this->location = $options['location'];
            }

            // Mode
            if (isset($options['mode']) && \is_int($options['mode'])) {
                $this->mode = $options['mode'];
            }
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->isConnected) {
            $this->close();
        }
    }

    /**
     * @return bool
     * @see DatabaseInterface::close()
     */
    public function close(): bool
    {
        return $this->db->close();
    }

    /**
     * @param string $query
     *
     * @return mixed
     */
    public function fetchSingle(string $query): mixed
    {
        return $this->db->querySingle($query);
    }

    /**
     * Prepares an SQL statement for execution
     *
     * @param string $query
     *
     * @return \SQLite3Stmt|bool Returns an SQLite3Stmt object on success or FALSE on failure.
     */
    public function prepare(string $query): \SQLite3Stmt|bool
    {
        return $this->db->prepare($query);
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    /**
     * @param string $query
     *
     * @return string
     * @see DatabaseInterface::escapeString()
     */
    public function escapeString(string $query): string
    {
        return \Sqlite3::escapeString($query);
    }

    /**
     * @return int
     */
    public function getLastInsertRowId(): int
    {
        return $this->db->lastInsertRowID();
    }

    /**
     * @return \SQLite3
     */
    public function getDb(): \SQLite3
    {
        return $this->db;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function checkConnection(): bool
    {
        $result = $this->db->query('SHOW TABLES;');
        if ($result !== false && $result->fetchArray()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $query
     *
     * @return array<int, array<string, mixed>>|bool|int|null
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function query(string $query): int|bool|array|null
    {
        if (($length = \strpos($query, ' ')) === false) {
            throw new \RuntimeException('$length must not be false.');
        }
        $command = \substr($query, 0, $length);

        return match (\strtoupper($command)) {
            'SELECT'           => $this->fetch($query),
            'UPDATE', 'DELETE' => $this->exec($query),
            'INSERT'           => $this->insert($query),
            default            => null,
        };
    }

    /**
     * @param string $query
     *
     * @return array<int, array<string, mixed>>|null
     * @throws \Throwable
     */
    public function fetch(string $query): ?array
    {
        while (true) {
            $result = null;
            try {
                $result = $this->db->query($query);
            } catch (\Throwable $ex) {
                if ($this->db->lastErrorCode() !== 5) {
                    $this->logger->warning($this->db->lastErrorMsg());
                    throw $ex;
                }
            }

            if ($result instanceof \SQLite3Result) {
                $rows = [];
                while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
                    $rows[] = $row;
                }

                return $rows;
            }

            if ($this->db->lastErrorCode() !== 5) {
                $this->logger->warning($this->db->lastErrorMsg());

                return null;
            }
        }
    }

    /**
     * Executes a result-less query against a given database
     *
     * @param string $query
     *
     * @return bool
     */
    public function exec(string $query): bool
    {
        return $this->db->exec($query);
    }

    /**
     * Sqlite Insert
     *
     * @param string $query
     *
     * @return bool|int
     */
    public function insert(string $query): bool|int
    {
        if ($this->db->exec($query)) {
            return $this->db->lastInsertRowID();
        }

        return false;
    }
}

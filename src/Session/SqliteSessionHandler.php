<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Session;

use Exception;
use Jtl\Connector\Core\Database\Sqlite3;
use Jtl\Connector\Core\Exception\DatabaseException;
use Jtl\Connector\Core\Exception\SessionException;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReturnTypeWillChange;
use Throwable;

class SqliteSessionHandler implements SessionHandlerInterface, LoggerAwareInterface
{
    protected LoggerInterface $logger;
    private int               $lifetime;
    private Sqlite3           $db;
    private string            $dbLocation;
    private string            $databaseDir;

    /**
     * SqliteSession constructor.
     *
     * @param string $databaseDir
     *
     * @throws SessionException
     * @throws DatabaseException
     * @throws \RuntimeException
     */
    public function __construct(string $databaseDir)
    {
        if (!\is_dir($databaseDir) && !\mkdir($databaseDir) && !\is_dir($databaseDir)) {
            throw new SessionException('Could not create sqlite database directory');
        }
        $this->databaseDir = $databaseDir;
        $this->logger      = new NullLogger();
        $this->lifetime    = (int)\ini_get('session.gc_maxlifetime');

        $dbLocation       = \sprintf('%s/connector.s3db', $databaseDir);
        $this->dbLocation = $dbLocation;
        $isNew            = !\file_exists($dbLocation);

        $sqlite3 = new Sqlite3();
        $sqlite3->connect(['location' => $dbLocation]);
        $this->db = $sqlite3;

        if ($isNew) {
            $this->initializeTables();
        }

        $this->db->exec('PRAGMA journal_mode = DELETE;');

    }

    /**
     * @return void
     * @throws \RuntimeException
     */
    protected function initializeTables(): void
    {
        $schemaQueries = [
            'CREATE TABLE [session] (' . "\n" .
            '   [sessionId] VARCHAR(255)  UNIQUE NOT NULL,' . "\n" .
            '   [sessionExpires] INTEGER  NOT NULL,' . "\n" .
            '   [sessionData] BLOB  NULL' . "\n" .
            ')',

            'CREATE INDEX [sessionIndex] ON [session](' . "\n" .
            '  [sessionId]  ASC,' . "\n" .
            '  [sessionExpires]  ASC' . "\n" .
            ')',
        ];

        $checkQuery  = 'SELECT name FROM sqlite_master WHERE type = \'table\' AND name = \'session\'';
        $checkResult = $this->db->fetchSingle($checkQuery);
        if ($checkResult === false) {
            throw new \RuntimeException('Something went wrong while checking existence of session schema');
        }

        if ($checkResult === null) {
            foreach ($schemaQueries as $query) {
                $this->db->exec($query);
            }
        }
    }

    /**
     * Open Session
     *
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     * @throws InvalidArgumentException
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    #[ReturnTypeWillChange]
    public function open(string $savePath, string $sessionName): bool
    {
        $this->logger->debug(
            'Open session with save path ({path}) and session name ({name})',
            ['path' => $savePath, 'name' => $sessionName]
        );

        return true;
    }

    /**
     * Close Sesssion
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function close(): bool
    {
        $this->logger->debug('Close session');

        return true;
    }

    /**
     * Read Session
     *
     * @param string $sessionId
     *
     * @return false|string
     * @throws Throwable
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    #[ReturnTypeWillChange]
    public function read(string $sessionId): bool|string
    {
        $sessionId = $this->db->escapeString($sessionId);
        $this->logger->debug('Read session with id ({id})', ['id' => $sessionId]);

        $rows = $this->db->query($this->createReadQuery($sessionId, \time()));
        if ($rows !== null && isset($rows[0])) {
            $row = $rows[0];
            if (isset($row['sessionData']) && \is_string($row['sessionData']) && $row['sessionData'] !== '') {
                return \base64_decode($row['sessionData'], true);
            }
        }

        return '';
    }

    /**
     * @param string $sessionId
     * @param int    $expiresAt
     *
     * @return string
     */
    protected function createReadQuery(string $sessionId, int $expiresAt): string
    {
        return \sprintf(
            'SELECT sessionId, sessionData FROM session WHERE sessionId = \'%s\' AND sessionExpires >= %d',
            $this->db->escapeString($sessionId),
            $expiresAt
        );
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     * @throws DatabaseException
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    #[ReturnTypeWillChange]
    public function write(string $sessionId, string $sessionData): bool
    {
        try {
            if ($this->db->checkConnection() === false) {
                $db = new Sqlite3();
                $db->connect(['location' => $this->dbLocation]);
                $this->db = $db;
            }
        } catch (Exception $e) {
            if (
                !\is_dir($this->databaseDir)
                && !\mkdir($concurrentDirectory = $this->databaseDir)
                && !\is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException('Could not create sqlite database directory');
            }
            $db = new Sqlite3();
            $db->connect(['location' => $this->dbLocation]);
            $this->db = $db;
        }
        $sessionId   = $this->db->escapeString($sessionId);
        $sessionData = \base64_encode($sessionData);
        $expire      = $this->calculateExpiryTime();
        $this->logger->debug('Write session with id ({id})', ['id' => $sessionId]);

        $db      = $this->db->getDb();
        $success = false;

        try {
            $stmt = $db->prepare(
                'INSERT INTO session (sessionId, sessionExpires, sessionData) VALUES(:session_id, :expire, :data)'
            );

            if ($stmt === false) {
                throw new \RuntimeException('db-statement must not be false.');
            }

            $stmt->bindValue(':session_id', $sessionId, \SQLITE3_TEXT);
            $stmt->bindValue(':expire', $expire, \SQLITE3_INTEGER);
            $stmt->bindValue(':data', $sessionData, \SQLITE3_TEXT);

            $stmt->execute();
            $success = true;
        } catch (\Throwable $ex) {
            if ($db->lastErrorCode() === 19) {
                /** @var \SQLite3Stmt $stmt */
                $stmt = $db->prepare(
                    'UPDATE session SET sessionData = :data,
                   sessionExpires = :expire WHERE sessionId = :session_id'
                );
                $stmt->bindValue(':data', $sessionData, \SQLITE3_TEXT);
                $stmt->bindValue(':expire', $expire, \SQLITE3_INTEGER);
                $stmt->bindValue(':session_id', $sessionId, \SQLITE3_TEXT);
                $stmt->execute();
                $success = true;
            }
        }

        return $success;
    }

    /**
     * @return int
     */
    protected function calculateExpiryTime(): int
    {
        return \time() + $this->lifetime;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     * @throws Throwable
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function destroy(string $sessionId): bool
    {
        $sessionId = $this->db->escapeString($sessionId);
        $this->logger->debug('Destroy session with id ({id})', ['id' => $sessionId]);
        $this->db->query(\sprintf('DELETE FROM session WHERE sessionId = \'%s\'', $sessionId));

        return true;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     * @throws Throwable
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    #[ReturnTypeWillChange]
    public function gc(int $maxLifetime): bool
    {
        $this->logger->debug(
            'Garbage collection for session with maximum lifetime ({maxLifetime})',
            ['maxLifetime' => $maxLifetime]
        );
        $this->db->query(\sprintf('DELETE FROM session WHERE sessionExpires < %d', \time()));

        return true;
    }

    /**
     * Checks if Session is Valid
     *
     * @param string $sessionId
     *
     * @return bool
     * @throws Throwable
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function validateId(string $sessionId): bool
    {
        $sessionId = $this->db->escapeString($sessionId);
        $this->logger->debug(
            'Check session with id ({id}) and time ({time}) ...',
            ['id' => $sessionId, 'time' => \time()]
        );
        $rows = $this->db->query($this->createReadQuery($sessionId, \time()));

        return $rows !== null
               && isset($rows[0]['sessionId'])
               && \is_string($rows[0]['sessionId'])
               && $rows[0]['sessionId'] === $sessionId;
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     * @throws \RuntimeException
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function updateTimestamp(string $sessionId, string $sessionData): bool
    {
        $sql  = 'UPDATE session SET sessionExpires = :sessionExpires WHERE sessionId = :sessionId';
        $stmt = $this->db->prepare($sql);
        if (\is_bool($stmt)) {
            throw new \RuntimeException('Statement must not be a boolean.');
        }
        $stmt->bindValue(':sessionExpires', $this->calculateExpiryTime(), \SQLITE3_INTEGER);
        $stmt->bindValue(':sessionId', $sessionId, \SQLITE3_TEXT);
        $stmt->execute();

        return true;
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
        $this->db->setLogger($logger);
    }
}

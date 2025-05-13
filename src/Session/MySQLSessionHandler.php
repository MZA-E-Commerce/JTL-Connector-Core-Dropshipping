<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Session;

use PDO;
use PDOException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReturnTypeWillChange;

class MySQLSessionHandler implements SessionHandlerInterface, LoggerAwareInterface
{
    protected LoggerInterface $logger;
    private PDO $pdo;
    private int $lifetime;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->logger = new NullLogger();
        $this->lifetime = (int)\ini_get('session.gc_maxlifetime');

        $this->initializeTable();
    }

    protected function initializeTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS session (
                sessionId VARCHAR(255) PRIMARY KEY,
                sessionExpires INT NOT NULL,
                sessionData MEDIUMTEXT
            );
        ";
        $this->pdo->exec($sql);
    }

    #[ReturnTypeWillChange]
    public function open($path, $name): bool
    {
        return true;
    }

    #[ReturnTypeWillChange]
    public function close(): bool
    {
        return true;
    }

    #[ReturnTypeWillChange]
    public function read($id): string|false
    {
        $stmt = $this->pdo->prepare("SELECT sessionData FROM session WHERE sessionId = :id AND sessionExpires >= :now");
        $stmt->execute([
            ':id' => $id,
            ':now' => time()
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? base64_decode((string)$result['sessionData'], true) : '';
    }

    #[ReturnTypeWillChange]
    public function write($id, $data): bool
    {
        $expire = time() + $this->lifetime;
        $encoded = base64_encode($data);

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO session ($id, sessionExpires, sessionData)
                VALUES (:id, :expires, :data)
                ON DUPLICATE KEY UPDATE sessionData = VALUES(sessionData), sessionExpires = VALUES(sessionExpires)
            ");
            $stmt->execute([
                ':id' => $id,
                ':expires' => $expire,
                ':data' => $encoded
            ]);
            return true;
        } catch (PDOException $e) {
            $this->logger->error("Session write failed: " . $e->getMessage());
            return false;
        }
    }

    #[ReturnTypeWillChange]
    public function destroy($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM session WHERE sessionId = :id");
        return $stmt->execute([':id' => $id]);
    }

    #[ReturnTypeWillChange]
    public function gc($max_lifetime): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM session WHERE sessionExpires < :time");
        return $stmt->execute([':time' => time()]);
    }

    public function validateId(string $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM session WHERE sessionId = :id AND sessionExpires >= :now");
        $stmt->execute([
            ':id' => $id,
            ':now' => time()
        ]);
        return $stmt->fetchColumn() !== false;
    }

    public function updateTimestamp(string $id, string $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE session SET sessionExpires = :expires
            WHERE sessionId = :id
        ");
        return $stmt->execute([
            ':expires' => time() + $this->lifetime,
            ':id' => $id
        ]);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Session;

use Jtl\Connector\Core\Database\Sqlite3;
use Jtl\Connector\Core\Exception\DatabaseException;
use Jtl\Connector\Core\Exception\SessionException;
use Jtl\Connector\Core\Session\SqliteSessionHandler;
use Jtl\Connector\Core\Test\TestCase;
use PDOStatement;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class SqliteSessionHandlerTest extends TestCase
{
    protected string               $dbFile;
    protected SqliteSessionHandler $handler;
    protected \PDO                 $pdo;

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testConstruct(): void
    {
        $this->assertFileExists($this->dbFile);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function testClose(): void
    {
        $this->assertTrue($this->handler->close());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testDestroy(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() + 1;
        $this->assertTrue($this->insertSessionData($sessionId, $sessionData, $expires));
        $this->handler->destroy($sessionId);
        $this->assertNull($this->findSessionData($sessionId));
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     * @param int    $expires
     *
     * @return bool
     * @throws \PDOException
     */
    protected function insertSessionData(string $sessionId, string $sessionData, int $expires): bool
    {
        $sql  = 'INSERT INTO session (sessionId, sessionData, sessionExpires) VALUES(:sid,:data,:expires)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('sid', $sessionId, \PDO::PARAM_STR);
        $stmt->bindValue('data', \base64_encode($sessionData), \PDO::PARAM_STR);
        $stmt->bindValue('expires', $expires, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() === 1;
    }

    /**
     * @param string $sessionId
     *
     * @return array<string, scalar>|null
     * @throws \PDOException
     */
    protected function findSessionData(string $sessionId): ?array
    {
        $sql  = 'SELECT * FROM session WHERE sessionId = :sid';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('sid', $sessionId, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (\is_array($data)) {
            $data['sessionData'] = \base64_decode($data['sessionData'], true);
            return $data;
        }
        return null;
    }

    /**
     * @return void
     * @throws DatabaseException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function testWriteInsert(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $now         = \time();
        $this->assertNull($this->findSessionData($sessionId));
        $this->handler->write($sessionId, $sessionData);
        $data = $this->findSessionData($sessionId);
        $this->assertIsArray($data);
        $this->assertEquals($sessionId, $data['sessionId']);
        $this->assertEquals($sessionData, $data['sessionData']);
        $this->assertGreaterThan($now, $data['sessionExpires']);
    }

    /**
     * @return void
     * @throws DatabaseException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function testWriteUpdate(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() + 1;
        $this->assertTrue($this->insertSessionData($sessionId, $sessionData, $expires));
        $newData = $this->getFaker()->text . '213';
        $this->handler->write($sessionId, $newData);
        $data = $this->findSessionData($sessionId);
        $this->assertIsArray($data);
        $this->assertEquals($sessionId, $data['sessionId']);
        $this->assertEquals($newData, $data['sessionData']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PDOException
     * @throws \Throwable
     */
    public function testGc(): void
    {
        foreach ([-2, -5, 4, 6, -7, -42] as $expireOffset) {
            $sessionId   = \uniqid('wtfsess', true);
            $sessionData = $this->getFaker()->text;
            $expires     = \time() + $expireOffset;
            $this->insertSessionData($sessionId, $sessionData, $expires);
        }
        $this->assertEquals(6, $this->countSessionData());
        $this->handler->gc(1234);
        $this->assertEquals(2, $this->countSessionData());
    }

    /**
     * @return int
     * @throws AssertionFailedError
     * @throws RuntimeException
     */
    protected function countSessionData(): int
    {
        $sql   = 'SELECT COUNT(sessionId) FROM session';
        $query = $this->pdo->query($sql);
        if ($query instanceof PDOStatement === false) {
            throw new \RuntimeException('$query must be instance of ' . PDOStatement::class);
        }

        $return = $query->fetchColumn();

        if (!\is_numeric($return)) {
            $this->fail('$return must be numeric.');
        }

        return (int)$return;
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PDOException
     * @throws \Throwable
     */
    public function testValidateIdSuccess(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() + 1;
        $this->insertSessionData($sessionId, $sessionData, $expires);
        $this->assertTrue($this->handler->validateId($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PDOException
     * @throws \Throwable
     */
    public function testValidateIdFailsSessionExpired(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() - 1;
        $this->insertSessionData($sessionId, $sessionData, $expires);
        $this->assertFalse($this->handler->validateId($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testValidateIdFailsSessionDoesNotExist(): void
    {
        $sessionId = \uniqid('wtfsess', true);
        $this->assertFalse($this->handler->validateId($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function testOpen(): void
    {
        $this->assertTrue($this->handler->open('foo', 'bar'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PDOException
     * @throws \Throwable
     */
    public function testReadSuccess(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() + 1;
        $this->insertSessionData($sessionId, $sessionData, $expires);
        $this->assertEquals($sessionData, $this->handler->read($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PDOException
     * @throws \Throwable
     */
    public function testReadFailedSessionExpired(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() - 1;
        $this->insertSessionData($sessionId, $sessionData, $expires);
        $this->assertEquals('', $this->handler->read($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function testReadFailedSessionNotExists(): void
    {
        $sessionId = \uniqid('wtfsess', true);
        $this->assertEquals('', $this->handler->read($sessionId));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     * @throws \PDOException
     */
    public function testUpdateTimestamp(): void
    {
        $sessionId   = \uniqid('wtfsess', true);
        $sessionData = $this->getFaker()->text;
        $expires     = \time() + 1;
        $this->insertSessionData($sessionId, $sessionData, $expires);
        $this->assertTrue($this->handler->updateTimestamp($sessionId, $sessionData));
        $data = $this->findSessionData($sessionId);
        $this->assertIsArray($data);
        $this->assertGreaterThan($expires, $data['sessionExpires']);
    }

    /**
     * @return void
     * @throws DatabaseException
     * @throws SessionException
     * @throws \PDOException
     * @throws \RuntimeException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new SqliteSessionHandler(\sprintf('%s/var', $this->connectorDir));
        $this->dbFile  = \sprintf('%s/var/connector.s3db', $this->connectorDir);
        $this->pdo     = new \PDO(
            \sprintf('sqlite:%s', $this->dbFile),
            null,
            null,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    protected function tearDown(): void
    {
        $refl = new \ReflectionClass($this->handler);
        $prop = $refl->getProperty('db');
        $prop->setAccessible(true);
        /** @var Sqlite3 $db */
        $db = $prop->getValue($this->handler);
        $db->close();
        parent::tearDown();
    }
}

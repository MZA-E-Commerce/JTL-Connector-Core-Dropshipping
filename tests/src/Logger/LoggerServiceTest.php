<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Logger;

use Composer\InstalledVersions;
use Jtl\Connector\Core\Exception\LoggerException;
use Jtl\Connector\Core\Logger\LoggerService;
use Jtl\Connector\Core\Rpc\Warnings;
use Jtl\Connector\Core\Test\TestCase;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\ClassIsReadonlyException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use Psr\Log\LogLevel;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class LoggerServiceTest extends TestCase
{
    protected LoggerService $factory;

    protected string $logDir;

    /**
     * @return void
     * @throws LoggerException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws \ReflectionException
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    public function testSetFormat(): void
    {
        $args = [JsonFormatter::BATCH_MODE_NEWLINES];
        $this->factory->setFormat('json', $args);
        $reflection    = new \ReflectionClass($this->factory);
        $formatterProp = $reflection->getProperty('formatter');
        $formatterProp->setAccessible(true);
        $formatter = $formatterProp->getValue($this->factory);
        $this->assertInstanceOf(JsonFormatter::class, $formatter);
    }

    /**
     * @return void
     * @throws LoggerException
     * @throws \ReflectionException
     * @throws \RuntimeException
     */
    public function testSetFormatFormatterNotFound(): void
    {
        $this->expectException(LoggerException::class);
        $this->expectExceptionCode(LoggerException::FORMATTER_NOT_EXISTS);
        $this->factory->setFormat('yolo');
    }

    /**
     * @return void
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     */
    public function testSetFormatter(): void
    {
        $formatterMock = $this->createMock(FormatterInterface::class);
        $this->factory->setFormatter($formatterMock);
        $reflection    = new \ReflectionClass($this->factory);
        $formatterProp = $reflection->getProperty('formatter');
        $formatterProp->setAccessible(true);
        $actualFormatter = $formatterProp->getValue($this->factory);
        $this->assertEquals($formatterMock, $actualFormatter);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testSetFormatterToExistingLoggers(): void
    {
        $hasNewLog = false;
        if (\substr(InstalledVersions::getVersion('psr/log') ?? '', 0, 1) === '3') {
            $hasNewLog = true;
        }

        $formatterMock = $this->createMock(FormatterInterface::class);
        foreach (['foo', 'bar', 'foobar'] as $channel) {
            $fooLogger = $this->factory->get($channel);
            $handlers  = $fooLogger->getHandlers();
            $this->assertArrayHasKey(0, $handlers);
            $this->assertArrayHasKey(1, $handlers);
            if ($hasNewLog) {
                $this->assertInstanceOf(FormattableHandlerInterface::class, $handlers[0]);
                $this->assertInstanceOf(FormattableHandlerInterface::class, $handlers[1]);
            } else {
                $this->assertInstanceOf(AbstractProcessingHandler::class, $handlers[0]);
                $this->assertInstanceOf(FingersCrossedHandler::class, $handlers[1]);
            }
            $this->assertNotEquals($formatterMock, $handlers[0]->getFormatter());
            $this->assertNotEquals($formatterMock, $handlers[1]->getFormatter());
        }
        $this->factory->setFormatter($formatterMock);
        foreach (['foo', 'bar', 'foobar'] as $channel) {
            $fooLogger = $this->factory->get($channel);
            $handlers  = $fooLogger->getHandlers();
            $this->assertArrayHasKey(0, $handlers);
            $this->assertArrayHasKey(1, $handlers);
            if ($hasNewLog) {
                $this->assertInstanceOf(FormattableHandlerInterface::class, $handlers[0]);
                $this->assertInstanceOf(FormattableHandlerInterface::class, $handlers[1]);
            } else {
                $this->assertInstanceOf(AbstractProcessingHandler::class, $handlers[0]);
                $this->assertInstanceOf(FingersCrossedHandler::class, $handlers[1]);
            }
            $this->assertEquals($formatterMock, $handlers[0]->getFormatter());
            $this->assertEquals($formatterMock, $handlers[1]->getFormatter());
        }
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \UnexpectedValueException
     */
    public function testGet(): void
    {
        $reflection   = new \ReflectionClass($this->factory);
        $channelsProp = $reflection->getProperty('channels');
        $channelsProp->setAccessible(true);
        $channels = $channelsProp->getValue($this->factory);
        $this->assertEmpty($channels);
        $fooChannel = $this->factory->get('foo');
        $channels   = $channelsProp->getValue($this->factory);
        $this->assertIsArray($channels);
        $this->assertArrayHasKey('foo', $channels);
        $this->assertEquals($fooChannel, $channels['foo']);
        $handlers = $fooChannel->getHandlers();
        $this->assertArrayHasKey(0, $handlers);
        $this->assertArrayHasKey(1, $handlers);
        $this->assertInstanceOf(RotatingFileHandler::class, $handlers[0]);
        $this->assertInstanceOf(FilterHandler::class, $handlers[1]);
        $this->assertEquals($fooChannel, $this->factory->get('foo'));

        $expectedLogFileName = \sprintf('%s/foo.log', $this->logDir);
        $handlerReflection   = new \ReflectionClass($handlers[0]);
        $filenameProp        = $handlerReflection->getProperty('filename');
        $filenameProp->setAccessible(true);
        $this->assertEquals($expectedLogFileName, $filenameProp->getValue($handlers[0]));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public function testHas(): void
    {
        $this->factory->get('foo');
        $this->assertTrue($this->factory->has('foo'));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHasNot(): void
    {
        $this->assertFalse($this->factory->has('bar'));
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \Psr\Log\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $warnings      = new Warnings();
        $this->logDir  = \sprintf('%s/var/log', $this->connectorDir);
        $this->factory = new LoggerService($this->logDir, LogLevel::DEBUG, $warnings);
    }
}

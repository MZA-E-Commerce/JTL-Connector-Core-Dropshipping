<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Definition\Action;
use Jtl\Connector\Core\Definition\Controller;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class EventTest
 *
 * @package Jtl\Connector\Core\Test\Definition
 */
class EventTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsMoment(): void
    {
        $this->assertTrue(Event::isMoment('before'));
        $this->assertTrue(Event::isMoment('after'));
        $this->assertFalse(Event::isMoment('After'));
        $this->assertFalse(Event::isMoment(' after'));
        $this->assertFalse(Event::isMoment('false'));
    }

    /**
     * @return void
     * @throws DefinitionException
     */
    public function testCreateRpcEventNameInvalidMoment(): void
    {
        $moment = 'justAfter';

        $this->expectExceptionObject(DefinitionException::unknownMoment($moment));

        Event::createRpcEventName($moment);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCreateRpcEventNameWithCorrectMoment(): void
    {
        $eventName = Event::createRpcEventName(Event::AFTER);
        $this->assertSame('rpc.after', $eventName);
    }

    /**
     * @return void
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CaseConverterException
     */
    public function testCreateHandleEventName(): void
    {
        $eventName = Event::createHandleEventName(Controller::PRODUCT, Action::PUSH, Event::AFTER);

        $this->assertSame('product.after.push.handle', $eventName);
    }

    /**
     * @return void
     * @throws CaseConverterException
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCreateCoreEventName(): void
    {
        $eventName = Event::createCoreEventName(Controller::PRODUCT, Action::PUSH, Event::AFTER);

        $this->assertSame('core.product.after.push', $eventName);
    }

    /**
     * @dataProvider createEventNameInvalidParamsDataProvider
     *
     * @param string     $controller
     * @param string     $action
     * @param string     $moment
     * @param \Exception $expectedException
     *
     * @return void
     * @throws CaseConverterException
     * @throws DefinitionException
     */
    public function testCreateEventNameInvalidParams(
        string     $controller,
        string     $action,
        string     $moment,
        \Exception $expectedException
    ): void {
        $this->expectExceptionObject($expectedException);

        Event::createEventName($controller, $action, $moment);
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: string, 3: DefinitionException}>
     */
    public function createEventNameInvalidParamsDataProvider(): array
    {
        return [
            ['foo', '', '', DefinitionException::unknownController('foo')],
            [Controller::PRODUCT, 'foo', '', DefinitionException::unknownAction('foo')],
            [Controller::PRODUCT, Action::PUSH, 'foo', DefinitionException::unknownMoment('foo')]
        ];
    }

    /**
     * @return void
     * @throws CaseConverterException
     * @throws DefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function createEventNameCorrectParams(): void
    {
        $eventName = Event::createEventName(Controller::PRODUCT, Action::PUSH, Event::BEFORE);

        $this->assertSame('product.before.push', $eventName);
    }
}

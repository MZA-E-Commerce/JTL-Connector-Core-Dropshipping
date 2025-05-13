<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jtl\Connector\Core\Definition\RpcMethod;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class RpcMethodTest
 *
 * @package Jtl\Connector\Core\Test\Definition
 */
class RpcMethodTest extends TestCase
{
    /**
     * @dataProvider isMethodDataProvider
     *
     * @param string $methodName
     * @param bool   $shouldBeMethod
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsMethod(string $methodName, bool $shouldBeMethod): void
    {
        $isMethodResult = RpcMethod::isMethod($methodName);
        $this->assertSame($shouldBeMethod, $isMethodResult);
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     * @throws \ReflectionException
     */
    public function isMethodDataProvider(): array
    {
        $definedMethods = $this->getCorrectConstantsTestCases(RpcMethod::class);

        $customTests   = [];
        $customTests[] = ['""', false];
        $customTests[] = [' ', false];
        $customTests[] = ['false', false];
        $customTests[] = ['true', false];
        $customTests[] = ['.', false];
        $customTests[] = [' connector.pull    ', true];
        $customTests[] = ['...', false];
        $customTests[] = ['.1.1.', false];
        $customTests[] = ['method\.name', false];
        $customTests[] = ['very.long.method.name', true];

        return \array_merge_recursive($definedMethods, $customTests);
    }

    /**
     * @dataProvider mapMethodDataProvider
     *
     * @param string $methodName
     * @param string $expectedMapping
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMapMethod(string $methodName, string $expectedMapping): void
    {
        $mappedName = RpcMethod::mapMethod($methodName);
        $this->assertSame($expectedMapping, $mappedName);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function mapMethodDataProvider(): array
    {
        return [
            [RpcMethod::CLEAR, 'core.connector.clear'],
            [RpcMethod::IDENTIFY, 'core.connector.identify'],
            [RpcMethod::FINISH, 'core.connector.finish'],
            [RpcMethod::AUTH, RpcMethod::AUTH],
            ['', ''],
            ['foo', 'foo']
        ];
    }
}

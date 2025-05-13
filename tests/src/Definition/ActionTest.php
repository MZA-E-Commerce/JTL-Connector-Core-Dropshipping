<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jtl\Connector\Core\Definition\Action;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ActionTest
 *
 * @package Jtl\Connector\Core\Test\Definition
 */
class ActionTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetActions(): void
    {
        $actions = Action::getActions();

        $this->assertSame(
            [
                Action::PULL,
                Action::PUSH,
                Action::DELETE,
                Action::STATISTIC,
                Action::AUTH,
                Action::ACK,
                Action::CLEAR,
                Action::FEATURES,
                Action::FINISH,
                Action::IDENTIFY,
                Action::INIT,
            ],
            \array_values($actions)
        );
    }

    /**
     * @dataProvider actionDataProvider
     *
     * @param string $actionName
     * @param bool   $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsAction(string $actionName, bool $expectedResult): void
    {
        $this->assertSame($expectedResult, Action::isAction($actionName));
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     */
    public function actionDataProvider(): array
    {
        return [
            [Action::PULL, true],
            [Action::CLEAR, true],
            ['push ', false],
            [' push ', false],
            ['PusH', false],
            ['Clear', false],
            [Action::CLEAR, true],
            ['false', false]
        ];
    }

    /**
     * @dataProvider coreActionDataProvider
     *
     * @param string $actionName
     * @param bool   $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsCoreAction(string $actionName, bool $expectedResult): void
    {
        $this->assertSame($expectedResult, Action::isCoreAction($actionName));
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     * @throws \Exception
     */
    public function coreActionDataProvider(): array
    {
        return [
            [Action::AUTH, true],
            [Action::ACK, true],
            [Action::PULL, false],
            ['false', false],
            ['autH', false],
            [(string)\random_int(-9999, 9999), false]
        ];
    }
}

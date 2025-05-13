<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jtl\Connector\Core\Definition\Controller;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class ControllerTest extends TestCase
{
    /**
     * @dataProvider isTypeDataProvider
     *
     * @param string $controllerName
     * @param bool   $shouldBeController
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsType(string $controllerName, bool $shouldBeController): void
    {
        $isType = Controller::isController($controllerName);
        $this->assertSame($shouldBeController, $isType);
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     * @throws \ReflectionException
     */
    public function isTypeDataProvider(): array
    {
        /** @var array<int, array{0: string, 1: bool}> $testCases */
        $testCases   = $this->getCorrectConstantsTestCases(Controller::class);
        $testCases[] = [
            'false',
            false,
        ];
        $testCases[] = ['', false,];
        $testCases[] = ['statuschange', false,];

        return $testCases;
    }
}

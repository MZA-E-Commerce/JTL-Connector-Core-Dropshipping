<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Definition;

use Jtl\Connector\Core\Definition\PaymentType;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class PaymentTypeTest
 *
 * @package Jtl\Connector\Core\Test\Definition
 */
class PaymentTypeTest extends TestCase
{
    /**
     * @dataProvider isTypeDataProvider
     *
     * @param string $type
     * @param bool   $shouldBePaymentType
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsType(string $type, bool $shouldBePaymentType): void
    {
        $isType = PaymentType::isType($type);
        $this->assertSame($shouldBePaymentType, $isType);
    }

    /**
     * @return array<int, array{0: string, 1: bool}>
     * @throws \ReflectionException
     */
    public function isTypeDataProvider(): array
    {
        /** @var array<int, array{0: string, 1: bool}> $testCases */
        $testCases   = $this->getCorrectConstantsTestCases(PaymentType::class);
        $testCases[] = ['false', false];
        $testCases[] = ['', false];
        $testCases[] = ['pm worldpay', false];
        $testCases[] = ['PM_WORLDPAY', false];

        return $testCases;
    }
}

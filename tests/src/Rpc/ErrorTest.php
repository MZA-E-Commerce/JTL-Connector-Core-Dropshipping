<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Rpc;

use Jtl\Connector\Core\Exception\RpcException;
use Jtl\Connector\Core\Rpc\Error;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\AssertionFailedError;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ErrorTest
 *
 * @package Jtl\Connector\Core\Test\Rpc
 */
class ErrorTest extends TestCase
{
    /**
     * @return void
     * @throws RpcException
     */
    public function testValidateThrowExceptionWhenCodeIsNull(): void
    {
        $this->expectExceptionObject(RpcException::parseError());

        $error = new Error();
        $error->validate();
    }

    /**
     * @return void
     * @throws RpcException
     */
    public function testValidateThrowExceptionWhenMessageIsNotSet(): void
    {
        $this->expectExceptionObject(RpcException::parseError());

        $error = new Error();
        $error->setCode(100);
        $error->setMessage('');
        $error->setData([]);
        $error->validate();
    }

    /**
     * @return void
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     */
    public function testValidateCorrect(): void
    {
        $error = new Error();
        $error->setCode(101);
        $error->setMessage('Error messasge');
        $error->setData([]);
        try {
            $error->validate();
        } catch (RpcException $rpcException) {
            $this->fail($rpcException->getMessage());
        }
        $this->assertIsObject($error);
    }
}

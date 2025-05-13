<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Rpc;

use Jtl\Connector\Core\Exception\RpcException;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Rpc\Error;
use Jtl\Connector\Core\Rpc\ResponsePacket;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ResponsePacketTest
 *
 * @package Jtl\Connector\Core\Test\Rpc
 */
class ResponsePacketTest extends TestCase
{
    /**
     * @dataProvider isValidDataProvider
     *
     * @param string     $id
     * @param Error|null $error
     * @param mixed      $result
     * @param string     $jtlRpc
     * @param bool       $isValid
     *
     * @return void
     * @throws RpcException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIsValid(string $id, ?Error $error, mixed $result, string $jtlRpc, bool $isValid): void
    {
        $responsePacket = new ResponsePacket();
        $responsePacket->setId($id);
        $responsePacket->setResult($result);
        if (!\is_null($error)) {
            $responsePacket->setError($error);
        }
        $responsePacket->setResult($responsePacket);
        $responsePacket->setJtlrpc($jtlRpc);

        $this->assertSame($isValid, $responsePacket->isValid());
    }

    /**
     * @return array<int, array<int, string|bool|Error|array{Products: array{0: Product}}|array{}|null>>
     */
    public function isValidDataProvider(): array
    {
        return [
            [
                '',
                null,
                null,
                '',
                false,
            ],
            [
                '123',
                (new Error())->setMessage('Error message'),
                null,
                '2.0',
                true,
            ],
            [
                '123',
                null,
                ['Products' => [new Product()]],
                '2.0',
                true,
            ],
            [
                '123',
                null,
                null,
                '2.0',
                true,
            ],
            [
                '123',
                null,
                null,
                '2.1',
                false,
            ],
            [
                '0',
                null,
                ['Products' => [new Product()]],
                '2.0',
                true,
            ],
            [
                ' ',
                null,
                [],
                '2.0',
                true,
            ],
        ];
    }
}

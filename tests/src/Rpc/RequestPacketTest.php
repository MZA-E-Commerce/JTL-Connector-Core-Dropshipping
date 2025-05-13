<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Rpc;

use Doctrine\Common\Annotations\AnnotationException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\SerializerBuilder;
use Jtl\Connector\Core\Rpc\RequestPacket;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class RequestPacketTest
 *
 * @package Jtl\Connector\Core\Test\Rpc
 */
class RequestPacketTest extends TestCase
{
    /**
     * @dataProvider validPacketDataProvider
     *
     * @param array{0: string, 1: string, 2: string, 3: array<mixed>} $inputParams
     * @param array{0: string, 1: string, 2: string, 3: array<mixed>} $expectedParams
     * @param bool                                                    $isValid
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValidPacket(array $inputParams, array $expectedParams, bool $isValid): void
    {
        $requestPacket = new RequestPacket();
        $requestPacket->setId($inputParams[0]);
        $requestPacket->setMethod($inputParams[1]);
        $requestPacket->setJtlrpc($inputParams[2]);
        $requestPacket->setParams($inputParams[3]);

        $this->assertSame($isValid, $requestPacket->isValid());
        $this->assertSame($expectedParams[1], $requestPacket->getMethod());
        $this->assertSame($expectedParams[2], $requestPacket->getJtlrpc());
        $this->assertSame($expectedParams[3], $requestPacket->getParams());
    }

    /**
     * @return array<int, array<int, array<int, string|array<int, string>|array{}|array{array{}}>|bool>>
     */
    public function validPacketDataProvider(): array
    {
        return [
            [
                [$id = (string)\time(), 'undefined.undefined', '2.0', [],],
                [
                    $id,
                    'undefined.undefined',
                    '2.0',
                    [],
                ],
                true,
            ],
            [
                [
                    '',
                    'undefined.undefined',
                    '2.0',
                    ['foo'],
                ],
                [
                    '',
                    'undefined.undefined',
                    '2.0',
                    ['foo'],
                ],
                false,
            ],
            [
                [
                    '1',
                    '',
                    '2.0',
                    ['foo'],
                ],
                [
                    '1',
                    '',
                    '2.0',
                    ['foo'],
                ],
                false,
            ],
            [
                [
                    '1',
                    'product.push',
                    '',
                    [[]],
                ],
                [
                    '1',
                    'product.push',
                    '',
                    [[]],
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider createFromJtlRpcDataProvider
     *
     * @param string                                       $jtlRpcInput
     * @param array{0: string, 1: string, 2: array<mixed>} $expectedParams
     * @param bool                                         $isValid
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws \JMS\Serializer\Exception\RuntimeException
     * @throws UnsupportedFormatException
     */
    public function testCreateFromJtlrpc(string $jtlRpcInput, array $expectedParams, bool $isValid): void
    {
        $requestPacket = RequestPacket::createFromJtlrpc($jtlRpcInput);


        $this->assertSame($expectedParams[0], $requestPacket->getJtlrpc());
        $this->assertSame($expectedParams[1], $requestPacket->getMethod());
        $this->assertSame($expectedParams[2], $requestPacket->getParams());
        $this->assertSame($isValid, $requestPacket->isValid());
    }

    /**
     * @return array<int, array<int, string|array<int, string|array{}|array{0: 'a', 1: 'b'}|array<int, int>>|bool>>
     */
    public function createFromJtlRpcDataProvider(): array
    {
        return [
            ['', ['', 'undefined.undefined', [],], false,],
            ['{"jtlrpc":"3.0","method":"jtlmethod"}', ['3.0', 'jtlmethod', [],], false,],
            ['{}', ['', 'undefined.undefined', [],], false,],
            ['{"id":"1"}', ['', 'undefined.undefined', [],], false,],
            ['{"jtlrpc":"2.0","id":"1"}', ['2.0', 'undefined.undefined', [],], true,],
            ['{"jtlrpc":"2.0","id":"1","params":["a","b"]}', ['2.0', 'undefined.undefined', ['a', 'b',],], true,],
            ['{"params":[1,2,3]}', ['', 'undefined.undefined', [1, 2, 3,],], false,],
        ];
    }

    /**
     * @dataProvider createFromJtlRpcDataProvider
     *
     * @param string                                       $jtlRpcInput
     * @param array{0: string, 1: string, 2: array<mixed>} $expectedParams
     * @param bool                                         $isValid
     *
     * @return void
     * @throws AnnotationException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws RuntimeException
     * @throws UnsupportedFormatException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws \JMS\Serializer\Exception\RuntimeException
     */
    public function testCreateFromJtlRpcUseAnotherSerializer(
        string $jtlRpcInput,
        array  $expectedParams,
        bool   $isValid
    ): void {
        $jmsSerializer = SerializerBuilder::create()->build();

        $requestPacket = RequestPacket::createFromJtlrpc($jtlRpcInput, $jmsSerializer);

        $this->assertSame($expectedParams[0], $requestPacket->getJtlrpc());
        $this->assertSame($expectedParams[1], $requestPacket->getMethod());
        $this->assertSame($expectedParams[2], $requestPacket->getParams());
        $this->assertSame($isValid, $requestPacket->isValid());
    }
}

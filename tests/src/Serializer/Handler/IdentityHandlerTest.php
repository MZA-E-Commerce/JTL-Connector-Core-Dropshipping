<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Serializer\Handler;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Serializer\Handler\IdentityHandler;
use Jtl\Connector\Core\Test\TestCase;

/**
 * Class IdentityHandlerTest
 *
 * @package Jtl\Connector\Core\Test\Serializer\Handler
 */
class IdentityHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testSerializeEntityMethod(): void
    {
        $endpointId = $this->createEndpointId();
        $hostId     = $this->createHostId();

        $identity = new Identity($endpointId, $hostId);

        $identityHandler = new IdentityHandler();
        $result          = $identityHandler->serializeIdentity(
            new JsonSerializationVisitor(),
            $identity,
            [],
            new SerializationContext()
        );

        $this->assertSame([$endpointId, $hostId], $result);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testDeserializeEntityMethod(): void
    {
        $endpointId = $this->createEndpointId();
        $hostId     = $this->createHostId();

        $identityHandler = new IdentityHandler();

        $identity = new Identity($endpointId, $hostId);

        $visitor = new JsonDeserializationVisitor();
        $visitor->setCurrentObject($identity);

        $result = $identityHandler->deserializeIdentity(
            $visitor,
            [
                $endpointId,
                $hostId,
            ],
            [],
            new DeserializationContext()
        );

        $this->assertEquals($result, $identity);
    }

    /**
     * @dataProvider identityDataProvider
     *
     * @param IdentityHandler          $identityHandler
     * @param array{0: string, 1: int} $identityArray
     *
     * @return void
     * @throws \Exception
     */
    public function testDeserializeEntityMultipleMethod(IdentityHandler $identityHandler, array $identityArray): void
    {
        $identity = new Identity($identityArray[0], $identityArray[1]);

        $visitor = new JsonDeserializationVisitor();
        $visitor->setCurrentObject($identity);

        $result = $identityHandler->deserializeIdentity(
            $visitor,
            $identityArray,
            [],
            new DeserializationContext()
        );

        $this->assertEquals($identity, $result);
    }

    /**
     * @return array<int, array<int, array<int, int|string>|IdentityHandler>>
     */
    public function identityDataProvider(): array
    {
        $identityHandler = new IdentityHandler();

        return [
            [$identityHandler, ['1', 1,],],
            [$identityHandler, ['2', 1,],],
            [$identityHandler, ['3', 2,],],
            [$identityHandler, ['1', 2,],],
        ];
    }
}

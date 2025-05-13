<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Serializer\Subscriber;

use Doctrine\Common\Annotations\AnnotationException;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\NotAcceptableException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\Exception\UnsupportedFormatException;
use Jtl\Connector\Core\Model\Ack;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\ManufacturerImage;
use Jtl\Connector\Core\Serializer\SerializerBuilder;
use Jtl\Connector\Core\Test\TestCase;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class ImageSubscriberTest extends TestCase
{
    /**
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
     */
    public function testOnPostSerialize(): void
    {
        $endpoint         = $this->getFaker()->word;
        $expectedEndpoint = \sprintf('manufacturer#=#%s', $endpoint);
        $expectedHost     = 123;
        $image            = (new ManufacturerImage())->setId(new Identity($endpoint, $expectedHost));
        $serializer       = SerializerBuilder::create()->build();
        $data             = $serializer->toArray($image);
        $this->assertEquals($expectedEndpoint, $data['id'][0]);
        $this->assertEquals($expectedHost, $data['id'][1]);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     * @throws \JMS\Serializer\Exception\InvalidArgumentException
     * @throws LogicException
     * @throws NotAcceptableException
     * @throws UnsupportedFormatException
     */
    public function testOnPostDeserialize(): void
    {
        $data = [
            'identities' => [
                'image' => [
                    ['category#=#yolo123', 42,],
                    ['manufacturer#=#yummy', 23,],
                ],
            ],
        ];

        $serializer = SerializerBuilder::create()->build();
        /** @var Ack $ack */
        $ack        = $serializer->fromArray($data, Ack::class);
        $identities = $ack->getIdentities();
        $this->assertArrayHasKey('categoryImage', $identities);
        $this->assertNotNull($identities['categoryImage']);
        $this->assertCount(1, $identities['categoryImage']);
        $this->assertEquals('yolo123', $identities['categoryImage'][0]->getEndpoint());
        $this->assertEquals(42, $identities['categoryImage'][0]->getHost());
        $this->assertArrayHasKey('manufacturerImage', $identities);
        $this->assertNotNull($identities['manufacturerImage']);
        $this->assertCount(1, $identities['manufacturerImage']);
        $this->assertEquals('yummy', $identities['manufacturerImage'][0]->getEndpoint());
        $this->assertEquals(23, $identities['manufacturerImage'][0]->getHost());
    }
}

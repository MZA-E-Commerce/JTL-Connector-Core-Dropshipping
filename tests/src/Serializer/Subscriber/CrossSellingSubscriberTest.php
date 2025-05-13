<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Serializer\Subscriber;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Jtl\Connector\Core\Model\CrossSelling;
use Jtl\Connector\Core\Serializer\Subscriber\CrossSellingSubscriber;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use PHPUnit\Framework\MockObject\ClassIsFinalException;
use PHPUnit\Framework\MockObject\ClassIsReadonlyException;
use PHPUnit\Framework\MockObject\DuplicateMethodException;
use PHPUnit\Framework\MockObject\InvalidMethodNameException;
use PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\UnknownTypeException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class CrossSellingSubscriberTest extends TestCase
{
    /**
     * @dataProvider crossSellingDataProvider
     *
     * @param array<mixed> $data
     * @param int          ...$expectedItemIds
     *
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws ClassAlreadyExistsException
     * @throws ClassIsFinalException
     * @throws ClassIsReadonlyException
     * @throws DuplicateMethodException
     * @throws InvalidMethodNameException
     * @throws OriginalConstructorInvocationRequiredException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws UnknownTypeException
     */
    public function testOnPreDeserialize(array $data, int ...$expectedItemIds): void
    {
        $context    = $this->createMock(DeserializationContext::class);
        $type       = [
            'name'   => CrossSelling::class,
            'params' => [],
        ];
        $event      = new PreDeserializeEvent($context, $data, $type);
        $subscriber = new CrossSellingSubscriber();
        $subscriber->onPreDeserialize($event);
        $eventData = $event->getData();
        $this->assertIsArray($eventData);
        $this->assertArrayHasKey('items', $eventData);
        $items = $eventData['items'];
        $this->assertIsArray($items);
        foreach ($items as $i => $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertIsArray($item['id']);
            $this->assertArrayHasKey(0, $item['id']);
            $this->assertArrayHasKey(1, $item['id']);
            $this->assertEquals($expectedItemIds[$i] ?? null, $item['id'][1]);
        }
    }

    /**
     * //phpcs:ignore Generic.Files.LineLength.TooLong, SlevomatCodingStandard.Commenting.DocCommentSpacing.IncorrectLinesCountBetweenDescriptionAndAnnotations
     * @return array<int, array<int, array<string, array<int, array<string, array<int, array<int, int|string>|int|string>>|int|string>>|int>>
     */
    public function crossSellingDataProvider(): array
    {
        $items = [
            [
                'crossSellingGroupId' => ['', 21,],
                'productIds'          => [['', 1,],],
            ],
            [
                'crossSellingGroupId' => ['', 42,],
                'productIds'          => [['', 1,],],
            ],
        ];

        return [
            [
                [
                    'id'        => ['', 100,],
                    'productId' => ['', 32,],
                    'items'     => $items,
                ],
                1452,
                2817,
            ],
        ];
    }
}

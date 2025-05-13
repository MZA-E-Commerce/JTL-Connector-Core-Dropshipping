<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Subscriber;

use InvalidArgumentException;
use Jtl\Connector\Core\Event\RpcEvent;
use Jtl\Connector\Core\Subscriber\RequestParamsTransformSubscriber;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\MethodCannotBeConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameAlreadyConfiguredException;
use PHPUnit\Framework\MockObject\MethodNameNotConfiguredException;
use PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException;
use PHPUnit\Framework\TestCase;

class RequestParamsTransformSubscriberTest extends TestCase
{
    /**
     * @dataProvider transformRequestParamsProvider
     *
     * @param RpcEvent $event
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws MethodCannotBeConfiguredException
     * @throws MethodNameAlreadyConfiguredException
     * @throws MethodNameNotConfiguredException
     * @throws MethodParametersAlreadyConfiguredException
     * @throws \RuntimeException
     */
    public function testTransformRequestParams(RpcEvent $event): void
    {
        $subscriber = $this->createPartialMock(
            RequestParamsTransformSubscriber::class,
            [
                'transformProductData',
                'transformProductPriceData',
                'transformProductStockLevelData'
            ]
        );

        $controller = $event->getController();
        $action     = $event->getAction();
        $data       = $event->getData();

        $subscriber
            ->expects($this->exactly($controller === 'Product' && $action === 'push' ? 1 : 0))
            ->method('transformProductData')
            ->with($data);

        $subscriber
            ->expects($this->exactly($controller === 'ProductPrice' && $action === 'push' ? 1 : 0))
            ->method('transformProductPriceData')
            ->with($data);

        $subscriber
            ->expects($this->exactly($controller === 'ProductStockLevel' && $action === 'push' ? 1 : 0))
            ->method('transformProductStockLevelData')
            ->with($data);

        $subscriber->transformRequestParams($event);
    }

    /**
     * @dataProvider transformProductProvider
     *
     * @param array<int, array<string, mixed>> $products
     * @param array<int, array<string, mixed>> $expectedResult
     *
     * @return void
     * @throws \RuntimeException
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testTransformProductData(array $products, array $expectedResult): void
    {
        $subscriber   = new RequestParamsTransformSubscriber();
        $actualResult = $subscriber->transformProductData($products);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider transformProductPriceProvider
     *
     * @param array<array<string, mixed>> $productPrices
     * @param array<int, mixed>           $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testTransformProductPriceData(array $productPrices, array $expectedResult): void
    {
        $subscriber   = new RequestParamsTransformSubscriber();
        $actualResult = $subscriber->transformProductPriceData($productPrices);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider transformProductStockLevelProvider
     *
     * @param array<int, array{productId: array{0: string, 1: int}, sku: ?string, stockLevel: ?float}> $productStock
     * @param array<int, array{id: array{0: string, 1: int}, sku: string, stockLevel: float}>          $expectedResult
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testTransformStockLevelData(array $productStock, array $expectedResult): void
    {
        $subscriber   = new RequestParamsTransformSubscriber();
        $actualResult = $subscriber->transformProductStockLevelData($productStock);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException|\Exception
     */
    public function testGetSubscribedEvents(): void
    {
        $subscribedEvents = RequestParamsTransformSubscriber::getSubscribedEvents();
        $this->assertTrue(isset($subscribedEvents['rpc.before'][0][0]));
        $this->assertTrue(isset($subscribedEvents['rpc.before'][0][1]));
        $this->assertEquals('transformRequestParams', $subscribedEvents['rpc.before'][0][0]);
        $this->assertEquals(10000, $subscribedEvents['rpc.before'][0][1]);
    }

    /**
     * @return array<int, array{0: RpcEvent}>
     */
    public function transformRequestParamsProvider(): array
    {
        return [
            [new RpcEvent(['foo', 'bar'], 'Product', 'push')],
            [new RpcEvent(['foo', 'bar'], 'Product', 'pull')],
            [new RpcEvent(['foo', 'bar'], 'ProductPrice', 'push')],
            [new RpcEvent(['foo', 'bar'], 'ProductPrice', 'statistic')],
            [new RpcEvent(['foo', 'bar'], 'ProductStockLevel', 'push')],
            [new RpcEvent(['foo', 'bar'], 'ProductStockLevel', 'yolo')],
            [new RpcEvent(['foo', 'bar'], 'Other', 'push')],
        ];
    }

    //phpcs:disable
    /**
     * @return array<int, array<int, array<int, array<string, array<int|string, array<string, array<int, array<string, array<int, int|string>|float|int>>>|float>|float|string>>>>
     */
    public function transformProductProvider(): array
    {
        //phpcs:enable
        return [
            [
                [
                    ['some' => 'thing'],
                    [
                        'foo'        => 'bar',
                        'stockLevel' => ['stockLevel' => 3.],
                    ],
                ],
                [
                    ['some' => 'thing'],
                    [
                        'foo'        => 'bar',
                        'stockLevel' => 3.,
                    ],
                ],
            ],
            [
                [['yo' => 'lo']],
                [['yo' => 'lo']],
            ],
            [
                [['stockLevel' => ['stockLevel' => 42.]]],
                [['stockLevel' => 42.]],
            ],
            [

                [
                    [
                        'prices' => [
                            [
                                'items' => [
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 10,
                                        'netPrice'       => 42.334,
                                    ],
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 0,
                                        'netPrice'       => 24.3697,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    [
                        'prices' => [
                            [
                                'items' => [
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 0,
                                        'netPrice'       => 24.3697,
                                    ],
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 10,
                                        'netPrice'       => 42.334,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

            ],
        ];
    }

    //phpcs:disable
    /**
     * @return array<int, array<int, array<int,array<string, array<int,array<string, array<int, array<string, array<int, int|string>|float|int>|int|string>|float|int|string>|int|string>|float|string>>>>
     */
    public function transformProductPriceProvider(): array
    {
        //phpcs:enable
        return [
            [
                [
                    [
                        'customerId'      => [
                            '',
                            0,
                        ],
                        'items'           => [
                            [
                                'productPriceId' => [
                                    '',
                                    1,
                                ],
                                'quantity'       => 10,
                                'netPrice'       => 42.334,
                            ],
                            [
                                'productPriceId' => [
                                    '',
                                    1,
                                ],
                                'quantity'       => 0,
                                'netPrice'       => 24.3697,
                            ],
                        ],
                        'customerGroupId' => [
                            'cfbd5018d38d41d8adca10d94fc8bdd6',
                            1,
                        ],
                        'sku'             => 'foo',
                        'vat'             => 19.0,
                        'taxClassId'      => [
                            '',
                            42,
                        ],
                        'id'              => [
                            '',
                            1,
                        ],
                        'productId'       => [
                            '',
                            1,
                        ],
                    ],
                    [
                        'customerId'      => [
                            '',
                            0,
                        ],
                        'items'           => [
                            [
                                'productPriceId' => [
                                    '',
                                    2,
                                ],
                                'quantity'       => 0,
                                'netPrice'       => 24.3697,
                            ],
                        ],
                        'customerGroupId' => [
                            '2fa802a6db864bd49fe41f5f3ed6d8e7',
                            2,
                        ],
                        'sku'             => 'foo',
                        'vat'             => 19.0,
                        'taxClassId'      => [
                            '',
                            42,
                        ],
                        'id'              => [
                            '',
                            2,
                        ],
                        'productId'       => [
                            '',
                            1,
                        ],
                    ],
                    2 => [
                        'customerId'      => [
                            '',
                            0,
                        ],
                        'items'           => [
                            [
                                'productPriceId' => [
                                    '',
                                    0,
                                ],
                                'quantity'       => 0,
                                'netPrice'       => 24.3697,
                            ],
                        ],
                        'customerGroupId' => [
                            '',
                            0,
                        ],
                        'sku'             => 'foo',
                        'vat'             => 19.0,
                        'taxClassId'      => [
                            '',
                            42,
                        ],
                        'id'              => [
                            '',
                            0,
                        ],
                        'productId'       => [
                            '',
                            1,
                        ],
                    ],
                ],
                [

                    [
                        'id'         => [
                            '',
                            1,
                        ],
                        'sku'        => 'foo',
                        'vat'        => 19.,
                        'taxClassId' => [
                            '',
                            42,
                        ],
                        'prices'     => [
                            [
                                'customerId'      => [
                                    '',
                                    0,
                                ],
                                'items'           => [
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 0,
                                        'netPrice'       => 24.3697,
                                    ],
                                    [
                                        'productPriceId' => [
                                            '',
                                            1,
                                        ],
                                        'quantity'       => 10,
                                        'netPrice'       => 42.334,
                                    ],
                                ],
                                'customerGroupId' => [
                                    'cfbd5018d38d41d8adca10d94fc8bdd6',
                                    1,
                                ],
                                'sku'             => 'foo',
                                'vat'             => 19.0,
                                'taxClassId'      => [
                                    '',
                                    42,
                                ],
                                'id'              => [
                                    '',
                                    1,
                                ],
                                'productId'       => [
                                    '',
                                    1,
                                ],
                            ],
                            [
                                'customerId'      => [
                                    '',
                                    0,
                                ],
                                'items'           => [
                                    [
                                        'productPriceId' => [
                                            '',
                                            2,
                                        ],
                                        'quantity'       => 0,
                                        'netPrice'       => 24.3697,
                                    ],
                                ],
                                'customerGroupId' => [
                                    '2fa802a6db864bd49fe41f5f3ed6d8e7',
                                    2,
                                ],
                                'sku'             => 'foo',
                                'vat'             => 19.0,
                                'taxClassId'      => [
                                    '',
                                    42,
                                ],
                                'id'              => [
                                    '',
                                    2,
                                ],
                                'productId'       => [
                                    '',
                                    1,
                                ],
                            ],
                            2 => [
                                'customerId'      => [
                                    '',
                                    0,
                                ],
                                'items'           => [
                                    [
                                        'productPriceId' => [
                                            '',
                                            0,
                                        ],
                                        'quantity'       => 0,
                                        'netPrice'       => 24.3697,
                                    ],
                                ],
                                'customerGroupId' => [
                                    '',
                                    0,
                                ],
                                'sku'             => 'foo',
                                'vat'             => 19.0,
                                'taxClassId'      => [
                                    '',
                                    42,
                                ],
                                'id'              => [
                                    '',
                                    0,
                                ],
                                'productId'       => [
                                    '',
                                    1,
                                ],
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     * @return array<int, array<int, array<int, array<string, array<int, int|string>|float|string>>>>
     */
    public function transformProductStockLevelProvider(): array
    {
        return [
            [
                [
                    [
                        'productId'  => [
                            '',
                            151,
                        ],
                        'stockLevel' => 37.0,
                        'sku'        => 'abcde',
                    ],
                    [
                        'productId'  => [
                            '',
                            159,
                        ],
                        'stockLevel' => 22.0,
                        'sku'        => '4u',
                    ],
                ],
                [
                    [
                        'id'         => [
                            '',
                            151,
                        ],
                        'stockLevel' => 37.0,
                        'sku'        => 'abcde',
                    ],
                    [
                        'id'         => [
                            '',
                            159,
                        ],
                        'stockLevel' => 22.0,
                        'sku'        => '4u',
                    ],
                ],
            ],
        ];
    }
}

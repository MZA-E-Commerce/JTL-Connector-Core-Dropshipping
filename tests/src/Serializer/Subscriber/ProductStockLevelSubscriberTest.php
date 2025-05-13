<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Serializer\Subscriber;

use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Serializer\SerializerBuilder;
use Jtl\Connector\Core\Test\TestCase;

class ProductStockLevelSubscriberTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testOnPostSerialize(): void
    {
        $stocklevel = \random_int(0, 999);
        $product    = (new Product())->setCreationDate(new \DateTimeImmutable());
        $product->setStockLevel($stocklevel);

        $serializer   = SerializerBuilder::create()->build();
        $productArray = $serializer->toArray($product);
        $this->assertEquals(['stockLevel' => $stocklevel], $productArray['stockLevel']);
    }
}

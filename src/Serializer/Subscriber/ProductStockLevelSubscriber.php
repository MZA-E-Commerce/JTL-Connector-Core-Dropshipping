<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Jtl\Connector\Core\Model\Product;

class ProductStockLevelSubscriber implements EventSubscriberInterface
{
    /**
     * @return array{0: array{event: string, method: string, format: string}}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event'  => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     *
     * @return void
     */
    public function onPostSerialize(ObjectEvent $event): void
    {
        $model = $event->getObject();
        if ($model instanceof Product) {
            $stockLevel = ['stockLevel' => $model->getStockLevel()];
            $event // @phpstan-ignore-line
            ->getVisitor()
            ->visitProperty(
                new StaticPropertyMetadata('', 'stockLevel', $stockLevel),
                $stockLevel
            );
        }
    }
}

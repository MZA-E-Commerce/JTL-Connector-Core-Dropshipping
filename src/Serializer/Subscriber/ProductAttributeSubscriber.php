<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\TranslatableAttribute;
use Jtl\Connector\Core\Model\TranslatableAttributeI18n;

class ProductAttributeSubscriber implements EventSubscriberInterface
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
     * @throws \RuntimeException
     */
    public function onPostSerialize(ObjectEvent $event): void
    {
        if ($event->getObject() instanceof TranslatableAttributeI18n) {
            $product = null;
            /** @var SerializationContext $context */
            $context     = $event->getContext();
            $visitingSet = $context->getVisitingSet();
            foreach ($visitingSet as $index => $element) {
                if ($element instanceof Product) {
                    $product = $element;
                    break;
                }
            }

            if (!\is_null($product)) {
                $visitingSet->offsetSet($product);
                $visitingSet->next();
                /** @var TranslatableAttribute $attribute */
                $attribute     = $visitingSet->current();
                $productAttrId = $attribute->getId()->toArray();
                $visitor       = $event->getVisitor();
                if ($visitor instanceof SerializationVisitorInterface === false) {
                    throw new \RuntimeException('$visitor must be instance of ' . SerializationVisitorInterface::class);
                }
                $visitor->visitProperty(
                    new StaticPropertyMetadata('', 'productAttrId', $productAttrId),
                    $productAttrId
                );
            }
        }
    }
}

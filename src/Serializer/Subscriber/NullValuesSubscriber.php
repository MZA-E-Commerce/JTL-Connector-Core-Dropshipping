<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

/**
 * Class PreDeserializeEvent
 *
 * @package Jtl\Connector\Core\Serializer\Event
 */
class NullValuesSubscriber implements EventSubscriberInterface
{
    /**
     * @return array{0: array{event: string, method: string, format: string}}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event'  => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'format' => 'json',
            ],
        ];
    }

    /**
     * Remove null values
     *
     * @param PreDeserializeEvent $event
     *
     * @return void
     */
    public function onPreDeserialize(PreDeserializeEvent $event): void
    {
        if (\is_array($eventData = $event->getData())) {
            $event->setData(
                \array_filter($eventData, static function ($value) {
                    return $value !== null;
                })
            );
        }
    }
}

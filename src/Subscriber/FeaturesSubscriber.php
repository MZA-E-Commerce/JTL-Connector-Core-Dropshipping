<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Subscriber;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Definition\Action;
use Jtl\Connector\Core\Definition\Controller;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Event\FeaturesEvent;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Model\FeatureFlag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FeaturesSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     * @throws DefinitionException|CaseConverterException
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Event::createCoreEventName(Controller::CONNECTOR, Action::FEATURES, Event::AFTER) => [
                [
                    'setNeedsFinishCallActive',
                    -10000,
                ],
            ],
        ];
    }

    /**
     * @param FeaturesEvent $event
     *
     * @return void
     */
    public function setNeedsFinishCallActive(FeaturesEvent $event): void
    {
        $event->getFeatures()->setFlag(new FeatureFlag('needs_finish_call', true));
    }
}

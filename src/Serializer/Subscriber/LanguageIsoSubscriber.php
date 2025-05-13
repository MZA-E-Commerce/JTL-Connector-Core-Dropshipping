<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Serializer\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Jtl\Connector\Core\Model\AbstractI18n;
use WhiteCube\Lingua\Service as Lingua;

class LanguageIsoSubscriber implements EventSubscriberInterface
{
    protected Lingua $languages;

    /**
     * LanguageIsoSubscriber constructor.
     */
    public function __construct()
    {
        $this->languages = new Lingua();
    }

    /**
     * @return array<int, array{event: string, method: string, format: string}>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event'  => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
            ],
            [
                'event'  => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
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
        if ($model instanceof AbstractI18n) {
            $languageIso = $model->getLanguageIso();
            if (\strlen($languageIso) === 2) {
                $languageIso = $this->languages->fromISO_639_1($languageIso)->toISO_639_2b();
            }

            $event->getVisitor()->visitProperty( // @phpstan-ignore-line
                new StaticPropertyMetadata('', 'languageISO', $languageIso),
                $languageIso
            );
        }
    }

    /**
     * @param PreDeserializeEvent $event
     *
     * @return void
     */
    public function onPreDeserialize(PreDeserializeEvent $event): void
    {
        $data = $event->getData();
        if (\is_array($data) && isset($data['languageISO']) && !isset($data['languageIso'])) {
            $language            = $this->languages->fromISO_639_2b($data['languageISO']);
            $data['languageIso'] = $language->toISO_639_1();
            $event->setData($data);
        }
    }
}

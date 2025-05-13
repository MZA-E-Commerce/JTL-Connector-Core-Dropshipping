<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Subscriber;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Definition\Event;
use Jtl\Connector\Core\Event\FeaturesEvent;
use Jtl\Connector\Core\Exception\DefinitionException;
use Jtl\Connector\Core\Exception\FeaturesException;
use Jtl\Connector\Core\Model\FeatureFlag;
use Jtl\Connector\Core\Model\Features;
use Jtl\Connector\Core\Subscriber\FeaturesSubscriber;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class FeaturesSubscriberTest extends TestCase
{
    /**
     * @return void
     * @throws FeaturesException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSetNeedsFinishCallActive(): void
    {
        $subscriber = new FeaturesSubscriber();
        $features   = Features::create();
        $this->assertFalse($features->hasFlag('needs_finish_call'));
        $subscriber->setNeedsFinishCallActive(new FeaturesEvent($features));
        $this->assertTrue($features->hasFlag('needs_finish_call'));
        $this->assertTrue($features->getFlag('needs_finish_call')->isActive());
    }

    /**
     * @return void
     * @throws FeaturesException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testSetNeedsFinishCallActiveOverrideInactive(): void
    {
        $subscriber = new FeaturesSubscriber();
        $features   = Features::create()->setFlag(new FeatureFlag('needs_finish_call'));
        $this->assertTrue($features->hasFlag('needs_finish_call'));
        $this->assertFalse($features->getFlag('needs_finish_call')->isActive());
        $subscriber->setNeedsFinishCallActive(new FeaturesEvent($features));
        $this->assertTrue($features->getFlag('needs_finish_call')->isActive());
    }

    /**
     * @return void
     * @throws CaseConverterException
     * @throws DefinitionException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGetSubscribedEvents(): void
    {
        $expectedEventName = Event::createCoreEventName('Connector', 'features', 'after');
        $subscribedEvents  = FeaturesSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey($expectedEventName, $subscribedEvents);
        $listeners = $subscribedEvents[$expectedEventName];
        $this->assertIsArray($listeners);
        $this->assertCount(1, $listeners);
        $this->assertArrayHasKey(0, $listeners);
        $event = $listeners[0];
        $this->assertIsArray($event);
        $this->assertCount(2, $event);
        $this->assertArrayHasKey(0, $event);
        $this->assertEquals('setNeedsFinishCallActive', $event[0]);
        $this->assertArrayHasKey(1, $event);
        /** @var array{0: string, 1: int} $event */
        $this->assertEquals(-10000, $event[1]);
    }
}

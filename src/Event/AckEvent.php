<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\Ack;
use Symfony\Contracts\EventDispatcher\Event;

class AckEvent extends Event
{
    protected Ack $ack;

    /**
     * AckEvent constructor.
     *
     * @param Ack $ack
     */
    public function __construct(Ack $ack)
    {
        $this->ack = $ack;
    }

    /**
     * @return Ack
     */
    public function getAck(): Ack
    {
        return $this->ack;
    }
}

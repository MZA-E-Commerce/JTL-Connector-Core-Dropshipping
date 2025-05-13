<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\DeliveryNote;
use Symfony\Contracts\EventDispatcher\Event;

class DeliveryNoteEvent extends Event
{
    protected DeliveryNote $deliveryNote;

    /**
     * DeliveryNoteEvent constructor.
     *
     * @param DeliveryNote $deliveryNote
     */
    public function __construct(DeliveryNote $deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }

    /**
     * @return DeliveryNote
     */
    public function getDeliveryNote(): DeliveryNote
    {
        return $this->deliveryNote;
    }
}

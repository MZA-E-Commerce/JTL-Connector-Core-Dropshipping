<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\CustomerOrder;
use Symfony\Contracts\EventDispatcher\Event;

class CustomerOrderEvent extends Event
{
    protected CustomerOrder $order;

    /**
     * CustomerOrderEvent constructor.
     *
     * @param CustomerOrder $order
     */
    public function __construct(CustomerOrder $order)
    {
        $this->order = $order;
    }

    /**
     * @return CustomerOrder
     */
    public function getOrder(): CustomerOrder
    {
        return $this->order;
    }
}

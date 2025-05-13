<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CustomerEvent extends Event
{
    protected Customer $customer;

    /**
     * CustomerEvent constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}

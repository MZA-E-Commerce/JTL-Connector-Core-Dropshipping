<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\Payment;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentEvent extends Event
{
    protected Payment $payment;

    /**
     * PaymentEvent constructor.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }
}

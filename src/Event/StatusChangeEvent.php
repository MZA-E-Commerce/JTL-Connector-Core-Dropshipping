<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\StatusChange;
use Symfony\Contracts\EventDispatcher\Event;

class StatusChangeEvent extends Event
{
    protected StatusChange $statusChange;

    /**
     * StatusChangeEvent constructor.
     *
     * @param StatusChange $statusChange
     */
    public function __construct(StatusChange $statusChange)
    {
        $this->statusChange = $statusChange;
    }

    /**
     * @return StatusChange
     */
    public function getStatusChange(): StatusChange
    {
        return $this->statusChange;
    }
}

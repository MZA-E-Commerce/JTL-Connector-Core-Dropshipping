<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\GlobalData;
use Symfony\Contracts\EventDispatcher\Event;

class GlobalDataEvent extends Event
{
    protected GlobalData $globalData;

    /**
     * GlobalDataEvent constructor.
     *
     * @param GlobalData $globalData
     */
    public function __construct(GlobalData $globalData)
    {
        $this->globalData = $globalData;
    }

    /**
     * @return GlobalData
     */
    public function getGlobalData(): GlobalData
    {
        return $this->globalData;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\CrossSelling;
use Symfony\Contracts\EventDispatcher\Event;

class CrossSellingEvent extends Event
{
    protected CrossSelling $crossSelling;

    /**
     * CrossSellingEvent constructor.
     *
     * @param CrossSelling $crossSelling
     */
    public function __construct(CrossSelling $crossSelling)
    {
        $this->crossSelling = $crossSelling;
    }

    /**
     * @return CrossSelling
     */
    public function getCrossSelling(): CrossSelling
    {
        return $this->crossSelling;
    }
}

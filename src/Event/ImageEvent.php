<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Model\AbstractImage;
use Symfony\Contracts\EventDispatcher\Event;

class ImageEvent extends Event
{
    protected AbstractImage $image;

    /**
     * ImageEvent constructor.
     *
     * @param AbstractImage $image
     */
    public function __construct(AbstractImage $image)
    {
        $this->image = $image;
    }

    /**
     * @return AbstractImage
     */
    public function getImage(): AbstractImage
    {
        return $this->image;
    }
}

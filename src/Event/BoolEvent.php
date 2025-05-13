<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;

class BoolEvent extends Event
{
    protected bool $result;

    /**
     * BoolEvent constructor.
     *
     * @param bool $result
     */
    public function __construct(bool &$result)
    {
        $this->result = &$result;
    }

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @param bool $result
     *
     * @return $this
     */
    public function setResult(bool $result): self
    {
        $this->result = $result;

        return $this;
    }
}

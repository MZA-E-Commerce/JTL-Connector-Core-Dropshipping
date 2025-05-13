<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RpcEvent
 *
 * @package Jtl\Connector\Core\Event
 */
class RpcEvent extends Event
{
    protected mixed $data;
    protected string $controller;
    protected string $action;

    /**
     * RpcEvent constructor.
     *
     * @param mixed  $data
     * @param string $controller
     * @param string $action
     */
    public function __construct(mixed $data, string $controller, string $action)
    {
        $this->data       = $data;
        $this->controller = $controller;
        $this->action     = $action;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return RpcEvent
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Error;

use Jtl\Connector\Core\Rpc\RequestPacket;

abstract class AbstractErrorHandler
{
    protected RequestPacket $requestPacket;

    /**
     * @return void
     */
    public function register(): void
    {
        \set_exception_handler($this->getExceptionHandler());
        \set_error_handler($this->getErrorHandler());
        \register_shutdown_function($this->getShutdownHandler());
    }

    /**
     * @return callable
     */
    abstract public function getExceptionHandler(): callable;

    /**
     * @return callable
     */
    abstract public function getErrorHandler(): callable;

    /**
     * @return callable
     */
    abstract public function getShutdownHandler(): callable;

    /**
     * @param RequestPacket $requestPacket
     *
     * @return $this
     */
    public function setRequestPacket(RequestPacket $requestPacket): self
    {
        $this->requestPacket = $requestPacket;

        return $this;
    }
}

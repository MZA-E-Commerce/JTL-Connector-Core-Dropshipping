<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Session;

use Psr\Log\LoggerInterface;

interface SessionHandlerInterface extends \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{
    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void;
}

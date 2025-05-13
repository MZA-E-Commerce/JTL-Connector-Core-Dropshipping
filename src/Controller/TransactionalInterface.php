<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

/**
 * Interface TransactionalInterface
 *
 * @package Jtl\Connector\Core\Controller
 */
interface TransactionalInterface
{
    /**
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * @return bool
     */
    public function commit(): bool;

    /**
     * @return bool
     */
    public function rollback(): bool;
}

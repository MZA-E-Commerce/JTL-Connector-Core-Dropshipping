<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Test\Stub\Controller;

use Jtl\Connector\Core\Controller\DeleteInterface;
use Jtl\Connector\Core\Controller\PullInterface;
use Jtl\Connector\Core\Controller\PushInterface;
use Jtl\Connector\Core\Controller\StatisticInterface;
use Jtl\Connector\Core\Controller\TransactionalInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\QueryFilter;

/**
 * Class TransactionalController
 *
 * @package Jtl\Connector\Core\Test\Stub\Controller
 */
class TransactionalControllerStub implements
    DeleteInterface,
    StatisticInterface,
    PullInterface,
    PushInterface,
    TransactionalInterface
{
    protected bool $commitThrowsException = false;

    /**
     * TransactionalControllerStub constructor.
     *
     * @param bool $commitThrowsException
     */
    public function __construct(bool $commitThrowsException = false)
    {
        $this->commitThrowsException = $commitThrowsException;
    }

    /**
     * @param QueryFilter $queryFilter
     *
     * @return int
     */
    public function statistic(QueryFilter $queryFilter): int
    {
        return 150;
    }

    /**
     * @param AbstractModel ...$model
     *
     * @return AbstractModel[]
     */
    public function delete(AbstractModel ...$model): array
    {
        return $model;
    }

    /**
     * @param QueryFilter $queryFilter
     *
     * @return array<int, AbstractModel>
     */
    public function pull(QueryFilter $queryFilter): array
    {
        $product1 = new Product('0', 0);
        $product1->setCreationDate(new \DateTimeImmutable());
        $product2 = new Product('1', 1);
        $product2->setCreationDate(new \DateTimeImmutable());
        return [$product1, $product2];
    }

    /**
     * @param AbstractModel ...$model
     *
     * @return AbstractModel[]
     */
    public function push(AbstractModel ...$model): array
    {
        return $model;
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return true;
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    public function commit(): bool
    {
        if ($this->commitThrowsException) {
            throw new \RuntimeException('Transaction exception');
        }
        return true;
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return true;
    }
}

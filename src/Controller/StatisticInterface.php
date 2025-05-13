<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\QueryFilter;

interface StatisticInterface
{
    /**
     * Statistic
     *
     * @param QueryFilter $queryFilter
     *
     * @return int
     */
    public function statistic(QueryFilter $queryFilter): int;
}

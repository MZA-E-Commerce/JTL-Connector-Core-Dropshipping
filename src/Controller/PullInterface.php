<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\QueryFilter;

interface PullInterface
{
    /**
     * Select
     *
     * @param QueryFilter $queryFilter
     *
     * @return AbstractModel[]
     */
    public function pull(QueryFilter $queryFilter): array;
}

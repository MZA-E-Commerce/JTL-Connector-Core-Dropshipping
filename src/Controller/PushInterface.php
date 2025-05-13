<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\AbstractModel;

interface PushInterface
{
    /**
     * Insert or update
     *
     * @param AbstractModel ...$model
     *
     * @return AbstractModel[]
     */
    public function push(AbstractModel ...$model): array;
}

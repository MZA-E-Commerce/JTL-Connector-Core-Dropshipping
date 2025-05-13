<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\AbstractModel;

interface DeleteInterface
{
    /**
     * Delete
     *
     * @param AbstractModel ...$model
     *
     * @return AbstractModel[]
     */
    public function delete(AbstractModel ...$model): array;
}

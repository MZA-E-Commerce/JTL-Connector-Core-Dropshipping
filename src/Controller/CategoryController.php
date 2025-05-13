<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\ProductStockLevel;

class CategoryController implements PushInterface
{
    /**
     * Insert or update
     *
     * @param AbstractModel ...$model
     *
     * @return AbstractModel[]
     */
    public function push(AbstractModel ...$model): array
    {
        # Host-ID => Die ID aus dem JTL-Wawi-System
        # Endpoint (ID) => Die ID im externen System (Pimcore, Shopware, Magento, etc.)

        return $model;
    }
}
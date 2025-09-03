<?php
namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Product;
use Psr\Log\LoggerInterface;

class StatusChangeController extends AbstractController
{
    public function __construct(CoreConfigInterface $config, LoggerInterface $logger)
    {
        parent::__construct($config, $logger);
    }

    protected function updateModel(Product $model): void
    {
    }

    public function push(AbstractModel ...$models): array
    {
        return $models;
    }
}
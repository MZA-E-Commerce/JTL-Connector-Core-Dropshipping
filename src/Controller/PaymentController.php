<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\QueryFilter;

class PaymentController extends AbstractController implements PullInterface
{
    protected function updateModel(Product $model): void
    {
        // TODO: Implement updateModel() method.
    }

    public function pull(QueryFilter $queryFilter): array
    {
        file_put_contents(Application::LOG_DIR . '/paymentPull.log', json_encode($queryFilter) . PHP_EOL . PHP_EOL);

        return [];
    }
}
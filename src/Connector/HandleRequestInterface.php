<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Connector;

use Jtl\Connector\Core\Application\Application;
use Jtl\Connector\Core\Application\Request;
use Jtl\Connector\Core\Application\Response;

interface HandleRequestInterface
{
    /**
     * @param Application $application
     * @param Request     $request
     *
     * @return Response
     */
    public function handle(Application $application, Request $request): Response;
}

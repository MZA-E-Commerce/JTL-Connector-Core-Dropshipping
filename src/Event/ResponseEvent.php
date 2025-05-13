<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Event;

use Jtl\Connector\Core\Application\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{
    protected Response $response;

    /**
     * ResponseEvent constructor.
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}

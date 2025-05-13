<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Connector;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getModelNamespace(): string;
}

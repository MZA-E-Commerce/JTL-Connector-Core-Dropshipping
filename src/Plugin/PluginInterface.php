<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Plugin;

use DI\Container;
use Noodlehaus\ConfigInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

interface PluginInterface
{
    /**
     * @param ConfigInterface $config
     * @param Container       $container
     * @param EventDispatcher $dispatcher
     *
     * @return mixed
     */
    public function registerListener( //phpcs:ignore
        ConfigInterface $config,
        Container           $container,
        EventDispatcher     $dispatcher
    );
}

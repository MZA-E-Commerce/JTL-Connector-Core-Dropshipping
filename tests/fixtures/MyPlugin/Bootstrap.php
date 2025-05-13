<?php

declare(strict_types=1);

namespace MyPlugin;

use DI\Container;
use Jtl\Connector\Core\Plugin\PluginInterface;
use Noodlehaus\ConfigInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Bootstrap implements PluginInterface
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
        Container       $container,
        EventDispatcher $dispatcher
    ) {
        // TODO: Implement registerListener() method.
        return null;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Connector;

use DI\Container;
use Jtl\Connector\Core\Authentication\TokenValidatorInterface;
use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Mapper\PrimaryKeyMapperInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

interface ConnectorInterface
{
    /**
     * @param CoreConfigInterface $config
     * @param Container           $container
     * @param EventDispatcher     $dispatcher
     *
     * @return void
     */
    public function initialize(CoreConfigInterface $config, Container $container, EventDispatcher $dispatcher): void;

    /**
     * @return PrimaryKeyMapperInterface
     */
    public function getPrimaryKeyMapper(): PrimaryKeyMapperInterface;

    /**
     * @return TokenValidatorInterface
     */
    public function getTokenValidator(): TokenValidatorInterface;

    /**
     * @return string
     */
    public function getControllerNamespace(): string;

    /**
     * @return string
     */
    public function getEndpointVersion(): string;

    /**
     * @return string
     */
    public function getPlatformVersion(): string;

    /**
     * @return string
     */
    public function getPlatformName(): string;
}

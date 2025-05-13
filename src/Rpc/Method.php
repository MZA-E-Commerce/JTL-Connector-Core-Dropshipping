<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Rpc;

use Jawira\CaseConverter\CaseConverterException;
use Jtl\Connector\Core\Definition\RpcMethod;
use Jtl\Connector\Core\Utilities\Str;

class Method
{
    /**
     * Rcp Method
     */
    protected string $rpcMethod = '';

    /**
     * Connector Controller
     */
    protected string $controller = '';

    /**
     * Connector Action
     */
    protected string $action = '';

    /**
     * Constructor
     *
     * @param string $rpcMethod
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $rpcMethod, string $controller, string $action)
    {
        $this->rpcMethod  = $rpcMethod;
        $this->controller = $controller;
        $this->action     = $action;
    }

    /**
     * @param RequestPacket $packet
     *
     * @return self
     * @throws CaseConverterException
     */
    public static function createFromRequestPacket(RequestPacket $packet): self
    {
        return static::createFromRpcMethod(RpcMethod::mapMethod($packet->getMethod()));
    }

    /**
     * @param string $rpcMethod
     *
     * @return self
     * @throws CaseConverterException
     */
    public static function createFromRpcMethod(string $rpcMethod): self
    {
        $parts      = \explode('.', $rpcMethod);
        $partsCount = \count($parts);
        if (!\in_array($partsCount, [2, 3], true)) {
            $parts = [
                'invalid',
                'invalid',
            ];
        }
        $offset     = $partsCount === 3 ? 1 : 0;
        $controller = Str::toPascalCase($parts[0 + $offset]);
        $action     = Str::toCamelCase($parts[1 + $offset]);

        return new self($rpcMethod, $controller, $action);
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return bool
     */
    public function isCore(): bool
    {
        return \str_contains($this->getRpcMethod(), 'core.');
    }

    /**
     * @return string
     */
    public function getRpcMethod(): string
    {
        return $this->rpcMethod;
    }
}

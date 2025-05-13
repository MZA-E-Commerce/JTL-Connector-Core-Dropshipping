<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Application;

class Request
{
    protected string $controller;
    protected string $action;
    /** @var mixed[] */
    protected array $params = [];

    /**
     * Request constructor.
     *
     * @param string  $controller
     * @param string  $action
     * @param mixed[] $params
     */
    public function __construct(string $controller, string $action, array $params)
    {
        $this->controller = $controller;
        $this->action     = $action;
        $this->params     = $params;
    }

    //TODO: brauchts das überhaupt noch? raus statt deprecated?

    /**
     * @param string  $controller
     * @param string  $action
     * @param mixed[] $params
     *
     * @return Request
     * @deprecated - check if still needed
     */
    public static function create(string $controller, string $action, array $params): self
    {
        return new self($controller, $action, $params);
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
     * @return mixed[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param mixed[] $params
     *
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }
}

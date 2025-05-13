<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Application;

class Response
{
    protected mixed $result;

    /**
     * Response constructor.
     *
     * @param mixed $result
     */
    public function __construct(mixed $result)
    {
        $this->setResult($result);
    }

    /**
     * @param mixed $result
     *
     * @return self
     */
    public static function create(mixed $result): self
    {
        return new self($result);
    }

    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     *
     * @return $this
     */
    public function setResult(mixed $result): self
    {
        $this->result = $result;
        return $this;
    }
}

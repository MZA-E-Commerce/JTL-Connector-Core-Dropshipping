<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Session;

use Jtl\Connector\Core\Exception\SessionException;

class SessionHelper
{
    protected string $namespace;

    /**
     * SessionHelper constructor.
     *
     * @param string $namespace
     *
     * @throws SessionException
     */
    public function __construct(string $namespace = 'default')
    {
        if ($namespace === '') {
            throw new SessionException('Session namespace must be a non-empty string.');
        }

        $this->namespace = $namespace;
    }

    /**
     * @param object $object
     *
     * @return self
     * @throws SessionException
     */
    public static function createByObjectClass(object $object): self
    {
        return new self(\get_class($object));
    }

    /**
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $_SESSION[$this->namespace][$name] ?? $default;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     * @throws SessionException
     */
    public function & __get(string $name): mixed
    {
        if ($name === '') {
            throw new SessionException("The '{$name}' key must be a non-empty string");
        }

        $value = &$_SESSION[$this->namespace][$name] ?? null;

        return $value;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     * @throws SessionException
     */
    public function __set(string $name, mixed $value): void
    {
        if ($name === '') {
            throw new SessionException("The '{$name}' key must be a non-empty string");
        }
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function set(string $name, mixed $value): self
    {
        $_SESSION[$this->namespace][$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     * @throws SessionException
     */
    public function __isset(string $name): bool
    {
        if ($name === '') {
            throw new SessionException("The '{$name}' key must be a non-empty string");
        }

        return $this->has($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($_SESSION[$this->namespace][$name]);
    }

    /**
     * @param string $name
     *
     * @return void
     * @throws SessionException
     */
    public function __unset(string $name): void
    {
        if ($name === '') {
            throw new SessionException("The '{$name}' key must be a non-empty string");
        }

        $this->unset($name);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function unset(string $name): self
    {
        if ($this->has($name)) {
            unset($_SESSION[$this->namespace][$name]);
        }

        return $this;
    }
}

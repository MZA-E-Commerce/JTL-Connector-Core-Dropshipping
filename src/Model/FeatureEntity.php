<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

class FeatureEntity
{
    protected string $name = '';

    protected bool $pull = false;

    protected bool $push = false;

    protected bool $delete = false;

    /**
     * FeatureEntity constructor.
     *
     * @param string $name
     * @param bool   $pull
     * @param bool   $push
     * @param bool   $delete
     */
    public function __construct(string $name, bool $pull = false, bool $push = false, bool $delete = false)
    {
        $this->name   = $name;
        $this->pull   = $pull;
        $this->push   = $push;
        $this->delete = $delete;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param bool $pull
     *
     * @return $this
     */
    public function setPull(bool $pull): self
    {
        $this->pull = $pull;

        return $this;
    }

    /**
     * @param bool $push
     *
     * @return $this
     */
    public function setPush(bool $push): self
    {
        $this->push = $push;

        return $this;
    }

    /**
     * @param bool $delete
     *
     * @return $this
     */
    public function setDelete(bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * @return array{pull: bool, push: bool, delete: bool}
     */
    public function toArray(): array
    {
        return [
            'pull'   => $this->canPull(),
            'push'   => $this->canPush(),
            'delete' => $this->canDelete(),
        ];
    }

    /**
     * @return bool
     */
    public function canPull(): bool
    {
        return $this->pull;
    }

    /**
     * @return bool
     */
    public function canPush(): bool
    {
        return $this->push;
    }

    /**
     * @return bool
     */
    public function canDelete(): bool
    {
        return $this->delete;
    }
}

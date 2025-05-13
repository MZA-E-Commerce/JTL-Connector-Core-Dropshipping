<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use Jtl\Connector\Core\Exception\FeaturesException;

class Features extends AbstractModel
{
    /** @var FeatureEntity[] */
    protected array $entities = [];

    /** @var FeatureFlag[] */
    protected array $flags = [];

    /**
     * Features constructor.
     *
     * @param FeatureEntity[] $entities
     * @param FeatureFlag[]   $flags
     */
    public function __construct(array $entities = [], array $flags = [])
    {
        $this->setEntities(...$entities);
        $this->setFlags(...$flags);
    }

    /**
     * @param array<string, array{pull?: ?bool, push?: ?bool, delete?: ?bool}>|array{} $entities
     * @param array<string, bool>|array{}                                              $flags
     *
     * @return self
     */
    public static function create(array $entities = [], array $flags = []): self
    {
        $featureEntities = [];
        foreach ($entities as $name => $methods) {
            $pull              = $methods['pull'] ?? false;
            $push              = $methods['push'] ?? false;
            $delete            = $methods['delete'] ?? false;
            $featureEntities[] = new FeatureEntity($name, $pull, $push, $delete);
        }

        $featureFlags = [];
        foreach ($flags as $name => $value) {
            $featureFlags[] = new FeatureFlag($name, $value);
        }

        return new self($featureEntities, $featureFlags);
    }

    /**
     * @param string $name
     *
     * @return FeatureEntity
     * @throws FeaturesException
     */
    public function getEntity(string $name): FeatureEntity
    {
        if ($this->hasEntity($name)) {
            return $this->entities[$name];
        }

        throw FeaturesException::entityNotFound($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasEntity(string $name): bool
    {
        return isset($this->entities[$name]);
    }

    /**
     * @return FeatureEntity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param FeatureEntity ...$entities
     *
     * @return $this
     */
    public function setEntities(FeatureEntity ...$entities): self
    {
        foreach ($entities as $entity) {
            $this->setEntity($entity);
        }

        return $this;
    }

    /**
     * @param FeatureEntity $entity
     *
     * @return $this
     */
    public function setEntity(FeatureEntity $entity): self
    {
        $this->entities[$entity->getName()] = $entity;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return FeatureFlag
     * @throws FeaturesException
     */
    public function getFlag(string $name): FeatureFlag
    {
        if ($this->hasFlag($name)) {
            return $this->flags[$name];
        }

        throw FeaturesException::flagNotFound($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFlag(string $name): bool
    {
        return isset($this->flags[$name]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isFlagActive(string $name): bool
    {
        if ($this->hasFlag($name)) {
            return $this->flags[$name]->isActive();
        }

        return false;
    }

    /**
     * @return FeatureFlag[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @param FeatureFlag ...$flags
     *
     * @return $this
     */
    public function setFlags(FeatureFlag ...$flags): self
    {
        foreach ($flags as $flag) {
            $this->setFlag($flag);
        }

        return $this;
    }

    /**
     * @param FeatureFlag $flag
     *
     * @return $this
     */
    public function setFlag(FeatureFlag $flag): self
    {
        $this->flags[$flag->getName()] = $flag;

        return $this;
    }

    /**
     * @return array{entities: array<string, array{pull: bool, push: bool, delete: bool}>, flags: array<string, bool>}
     */
    public function toArray(): array
    {
        $data = [
            'entities' => [],
            'flags'    => [],
        ];

        foreach ($this->entities as $entity) {
            $data['entities'][$entity->getName()] = $entity->toArray();
        }

        foreach ($this->flags as $flag) {
            $data['flags'][$flag->getName()] = $flag->isActive();
        }

        return $data;
    }
}

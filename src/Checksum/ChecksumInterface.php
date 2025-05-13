<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Checksum;

use Jtl\Connector\Core\Model\Identity;

interface ChecksumInterface
{
    /**
     * @param Identity $foreignKey
     *
     * @return $this
     */
    public function setForeignKey(Identity $foreignKey): self;

    /**
     * @return Identity
     */
    public function getForeignKey(): Identity;

    /**
     * @param string $endpoint
     *
     * @return $this
     */
    public function setEndpoint(string $endpoint): self;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): self;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @param int $type
     *
     * @return $this
     */
    public function setType(int $type): self;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @param bool $hasChanged
     *
     * @return $this
     */
    public function setHasChanged(bool $hasChanged): self;

    /**
     * @return bool
     */
    public function hasChanged(): bool;
}

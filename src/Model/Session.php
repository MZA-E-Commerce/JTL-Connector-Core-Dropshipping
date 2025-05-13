<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Session
 *
 * @package Jtl\Connector\Core\Model
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Session extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('sessionId')]
    #[Serializer\Accessor(getter: 'getSessionId', setter: 'setSessionId')]
    protected ?string $sessionId = null;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('lifetime')]
    #[Serializer\Accessor(getter: 'getLifetime', setter: 'setLifetime')]
    protected int $lifetime = 0;

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     *
     * @return $this
     */
    public function setLifetime(int $lifetime): self
    {
        $this->lifetime = $lifetime;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractIdentity extends AbstractModel implements IdentityInterface
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('id')]
    #[Serializer\Accessor(getter: 'getId', setter: 'setId')]
    protected Identity $id;

    /**
     * AbstractIdentity constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        $this->id = new Identity($endpoint, $host);
    }

    /**
     * @inheritDoc
     */
    public function getId(): Identity
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId(Identity $identity): self
    {
        $this->id = $identity;

        return $this;
    }
}

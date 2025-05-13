<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Identities
 *
 * @package Jtl\Connector\Core\Model
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Identities extends AbstractModel
{
    /** @var array<string, array<int, Identity>|null> */
    #[Serializer\Type('array<string, array<Jtl\Connector\Core\Model\Identity>>')]
    #[Serializer\SerializedName('identities')]
    #[Serializer\Accessor(getter: 'getIdentities', setter: 'setIdentities')]
    protected array $identities = [];

    /**
     * Identities getter
     *
     * @return array<string, array<int, Identity>|null>
     */
    public function getIdentities(): array
    {
        return $this->identities;
    }

    /**
     * Identities getter
     *
     * @param array<string, array<int, Identity>|null> $identities
     *
     * @return $this
     */
    public function setIdentities(array $identities): self
    {
        $this->identities = $identities;

        return $this;
    }
}

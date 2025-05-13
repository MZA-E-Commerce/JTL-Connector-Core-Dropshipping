<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

interface IdentityInterface
{
    /**
     * @return Identity
     */
    public function getId(): Identity;

    /**
     * @param Identity $identity
     *
     * @return $this
     */
    public function setId(Identity $identity): self;
}

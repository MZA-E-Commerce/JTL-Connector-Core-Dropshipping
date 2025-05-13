<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

#[Serializer\AccessType(['value' => 'public_method'])]
class Authentication extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('token')]
    #[Serializer\Accessor(getter: 'getToken', setter: 'setToken')]
    protected string $token = '';

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}

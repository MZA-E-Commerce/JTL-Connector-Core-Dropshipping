<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class KeyValueAttribute
 *
 * @access  public
 * @package Jtl\Connector\Core\Model\Customer
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class KeyValueAttribute extends AbstractModel
{
    /** @var string Attribute key */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('key')]
    #[Serializer\Accessor(getter: 'getKey', setter: 'setKey')]
    protected string $key = '';

    /** @var string Attribute value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('value')]
    #[Serializer\Accessor(getter: 'getValue', setter: 'setValue')]
    protected string $value = '';

    /**
     * @return string Attribute key
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key Attribute key
     *
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string Attribute value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value Attribute value
     *
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}

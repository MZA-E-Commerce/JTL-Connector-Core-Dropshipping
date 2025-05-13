<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Shipping classes are usually defined in JTL-Wawi ERP.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ShippingClass extends AbstractIdentity
{
    /** @var string Optional (internal) Shipping class name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string Optional (internal) Shipping class name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Optional (internal) Shipping class name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

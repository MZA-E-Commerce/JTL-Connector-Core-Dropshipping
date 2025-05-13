<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Customer group price for config item.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ConfigItemPrice extends AbstractModel
{
    /** @var Identity Reference to customerGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerGroupId')]
    #[Serializer\Accessor(getter: 'getCustomerGroupId', setter: 'setCustomerGroupId')]
    protected Identity $customerGroupId;

    /**
     * @var double  Net price or percental value to add/deduct to/from product price (depending on type).
     *              Positive value means surcharge, negative value means discount.
     *              Also see configItem.vat for value added tax.
     */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('price')]
    #[Serializer\Accessor(getter: 'getPrice', setter: 'setPrice')]
    protected float $price = 0.0;

    /** @var int Optional type. Default is fixed price (Type 0). Type 1 defines percental price type. */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected int $type = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerGroupId = new Identity();
    }

    /**
     * @return Identity Reference to customerGroup
     */
    public function getCustomerGroupId(): Identity
    {
        return $this->customerGroupId;
    }

    /**
     * @param Identity $customerGroupId Reference to customerGroup
     *
     * @return $this
     */
    public function setCustomerGroupId(Identity $customerGroupId): self
    {
        $this->customerGroupId = $customerGroupId;

        return $this;
    }

    /**
     * @return float   Net price or percental value to add/deduct to/from product price (depending on type).
     *                  Positive value means surcharge, negative value means discount.
     *                  Also see configItem.vat for value added tax.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price Net price or percental value to add/deduct to/from product price (depending on type).
     *                     Positive value means surcharge, negative value means discount.
     *                     Also see configItem.vat for value added tax.
     *
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int Optional type. Default is fixed price (Type 0). Type 1 defines percental price type.
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type Optional type. Default is fixed price (Type 0). Type 1 defines percental price type.
     *
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Link customergroup with category. Set optional discount on category for customergroup.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CategoryCustomerGroup extends AbstractModel
{
    /** @var Identity Reference to customerGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerGroupId')]
    #[Serializer\Accessor(getter: 'getCustomerGroupId', setter: 'setCustomerGroupId')]
    protected Identity $customerGroupId;

    /** @var double Optional discount on products in specified categoryId for  customerGroupId */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('discount')]
    #[Serializer\Accessor(getter: 'getDiscount', setter: 'setDiscount')]
    protected float $discount = 0.0;

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
     * @return float Optional discount on products in specified categoryId for  customerGroupId
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount Optional discount on products in specified categoryId for  customerGroupId
     *
     * @return $this
     */
    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}

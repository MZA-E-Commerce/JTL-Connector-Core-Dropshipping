<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Packaging quantities for customergroups.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CustomerGroupPackagingQuantity extends AbstractModel
{
    /** @var Identity Reference to customergroup. */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerGroupId')]
    #[Serializer\Accessor(getter: 'getCustomerGroupId', setter: 'setCustomerGroupId')]
    protected Identity $customerGroupId;

    /** @var double Contains the minimum quantity for a customergroup. */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('minimumOrderQuantity')]
    #[Serializer\Accessor(getter: 'getMinimumOrderQuantity', setter: 'setMinimumOrderQuantity')]
    protected float $minimumOrderQuantity = 0.0;

    /** @var double Product can only be purchased in multiples of takeOffQuantity e.g. 5,10,15... */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('packagingQuantity')]
    #[Serializer\Accessor(getter: 'getPackagingQuantity', setter: 'setPackagingQuantity')]
    protected float $packagingQuantity = 0.0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerGroupId = new Identity();
    }

    /**
     * @return Identity Reference to customergroup.
     */
    public function getCustomerGroupId(): Identity
    {
        return $this->customerGroupId;
    }

    /**
     * @param Identity $customerGroupId Reference to customer group.
     *
     * @return $this
     */
    public function setCustomerGroupId(Identity $customerGroupId): self
    {
        $this->customerGroupId = $customerGroupId;

        return $this;
    }

    /**
     * @return float Contains the minimum quantity for a customergroup.
     */
    public function getMinimumOrderQuantity(): float
    {
        return $this->minimumOrderQuantity;
    }

    /**
     * @param float $minimumOrderQuantity Contains the minimum quantity for a customergroup.
     *
     * @return $this
     */
    public function setMinimumOrderQuantity(float $minimumOrderQuantity): self
    {
        $this->minimumOrderQuantity = $minimumOrderQuantity;

        return $this;
    }

    /**
     * @return float Product can only be purchased in multiples of takeOffQuantity e.g. 5,10,15...
     */
    public function getPackagingQuantity(): float
    {
        return $this->packagingQuantity;
    }

    /**
     * @param float $packagingQuantity Product can only be purchased in multiples of takeOffQuantity e.g. 5,10,15...
     *
     * @return $this
     */
    public function setPackagingQuantity(float $packagingQuantity): self
    {
        $this->packagingQuantity = $packagingQuantity;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;

/**
 * Extra charge for productVariationValue per customerGroup.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductVariationValueExtraCharge extends AbstractModel
{
    /** @var Identity Reference to customerGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerGroupId')]
    #[Serializer\Accessor(getter: 'getCustomerGroupId', setter: 'setCustomerGroupId')]
    protected Identity $customerGroupId;

    /** @var double Extra charge (net) */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('extraChargeNet')]
    #[Serializer\Accessor(getter: 'getExtraChargeNet', setter: 'setExtraChargeNet')]
    protected float $extraChargeNet = 0.0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerGroupId = new Identity();
    }

    /**
     * @return Identity Reference to customerGroup
     * @throws MustNotBeNullException|\TypeError
     */
    public function getCustomerGroupId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->customerGroupId);
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
     * @return float Extra charge (net)
     */
    public function getExtraChargeNet(): float
    {
        return $this->extraChargeNet;
    }

    /**
     * @param float $extraChargeNet Extra charge (net)
     *
     * @return $this
     */
    public function setExtraChargeNet(float $extraChargeNet): self
    {
        $this->extraChargeNet = $extraChargeNet;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class StatusChange extends AbstractModel
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerOrderId')]
    #[Serializer\Accessor(getter: 'getCustomerOrderId', setter: 'setCustomerOrderId')]
    protected Identity $customerOrderId;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('orderStatus')]
    #[Serializer\Accessor(getter: 'getOrderStatus', setter: 'setOrderStatus')]
    protected string $orderStatus = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('paymentStatus')]
    #[Serializer\Accessor(getter: 'getPaymentStatus', setter: 'setPaymentStatus')]
    protected string $paymentStatus = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerOrderId = new Identity();
    }

    /**
     * @return Identity|null
     */
    public function getCustomerOrderId(): ?Identity
    {
        return $this->customerOrderId;
    }

    /**
     * @param Identity $customerOrderId
     *
     * @return $this
     */
    public function setCustomerOrderId(Identity $customerOrderId): self
    {
        $this->customerOrderId = $customerOrderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderStatus(): string
    {
        return $this->orderStatus;
    }

    /**
     * @param string $orderStatus
     *
     * @return $this
     */
    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     *
     * @return $this
     */
    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return string[]
     * @throws MustNotBeNullException|\TypeError
     */
    public function getIdentificationStrings(): array
    {
        $this->setIdentificationStringBySubject(
            'customerOrderId',
            \sprintf('JTL-Wawi PK = %d', Validate::checkIdentityAndNotNull($this->customerOrderId)->getHost())
        );

        return parent::getIdentificationStrings();
    }
}

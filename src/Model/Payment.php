<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Payment extends AbstractIdentity
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerOrderId')]
    #[Serializer\Accessor(getter: 'getCustomerOrderId', setter: 'setCustomerOrderId')]
    protected Identity $customerOrderId;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('billingInfo')]
    #[Serializer\Accessor(getter: 'getBillingInfo', setter: 'setBillingInfo')]
    protected string $billingInfo = '';

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('paymentModuleCode')]
    #[Serializer\Accessor(getter: 'getPaymentModuleCode', setter: 'setPaymentModuleCode')]
    protected string $paymentModuleCode = '';


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('totalSum')]
    #[Serializer\Accessor(getter: 'getTotalSum', setter: 'setTotalSum')]
    protected float $totalSum = 0.0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('transactionId')]
    #[Serializer\Accessor(getter: 'getTransactionId', setter: 'setTransactionId')]
    protected string $transactionId = '';

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->customerOrderId = new Identity();
    }

    /**
     * @return Identity
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getCustomerOrderId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->customerOrderId);
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
    public function getBillingInfo(): string
    {
        return $this->billingInfo;
    }

    /**
     * @param string $billingInfo
     *
     * @return $this
     */
    public function setBillingInfo(string $billingInfo): self
    {
        $this->billingInfo = $billingInfo;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface|null $creationDate
     *
     * @return $this
     */
    public function setCreationDate(?\DateTimeInterface $creationDate = null): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentModuleCode(): string
    {
        return $this->paymentModuleCode;
    }

    /**
     * @param string $paymentModuleCode
     *
     * @return $this
     */
    public function setPaymentModuleCode(string $paymentModuleCode): self
    {
        $this->paymentModuleCode = $paymentModuleCode;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalSum(): float
    {
        return $this->totalSum;
    }

    /**
     * @param float $totalSum
     *
     * @return $this
     */
    public function setTotalSum(float $totalSum): self
    {
        $this->totalSum = $totalSum;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return $this
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }
}

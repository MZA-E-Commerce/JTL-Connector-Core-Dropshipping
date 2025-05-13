<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use DateTimeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Customer order properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CustomerOrder extends AbstractI18n implements IdentityInterface, ItemsInterface
{
    public const
        PAYMENT_STATUS_COMPLETED = 'completed',
        PAYMENT_STATUS_PARTIALLY = 'partially_paid',
        PAYMENT_STATUS_UNPAID    = 'unpaid',
        STATUS_NEW               = 'new',
        STATUS_CANCELLED         = 'cancelled',
        STATUS_PARTIALLY_SHIPPED = 'partially_shipped',
        STATUS_SHIPPED           = 'shipped';

    /** @var Identity Optional reference to customer. */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerId')]
    #[Serializer\Accessor(getter: 'getCustomerId', setter: 'setCustomerId')]
    protected Identity $customerId;

    /** @var Identity Unique customerOrder id */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('id')]
    #[Serializer\Accessor(getter: 'getId', setter: 'setId')]
    protected Identity $id;

    /** @var CustomerOrderBillingAddress|null Billing address */
    #[Serializer\Type(CustomerOrderBillingAddress::class)]
    #[Serializer\SerializedName('billingAddress')]
    #[Serializer\Accessor(getter: 'getBillingAddress', setter: 'setBillingAddress')]
    protected ?CustomerOrderBillingAddress $billingAddress = null;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('carrierName')]
    #[Serializer\Accessor(getter: 'getCarrierName', setter: 'setCarrierName')]
    protected string $carrierName = '';

    /** @var \DateTimeInterface|null Date of creation */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('currencyIso')]
    #[Serializer\Accessor(getter: 'getCurrencyIso', setter: 'setCurrencyIso')]
    protected string $currencyIso = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('customerNote')]
    #[Serializer\Accessor(getter: 'getCustomerNote', setter: 'setCustomerNote')]
    protected string $customerNote = '';

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('estimatedDeliveryDate')]
    #[Serializer\Accessor(getter: 'getEstimatedDeliveryDate', setter: 'setEstimatedDeliveryDate')]
    protected ?\DateTimeInterface $estimatedDeliveryDate = null;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    /** @var string Optional order number (usually set by ERP System later) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('orderNumber')]
    #[Serializer\Accessor(getter: 'getOrderNumber', setter: 'setOrderNumber')]
    protected string $orderNumber = '';

    /** @var \DateTimeInterface|null Payment date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('paymentDate')]
    #[Serializer\Accessor(getter: 'getPaymentDate', setter: 'setPaymentDate')]
    protected ?\DateTimeInterface $paymentDate = null;

    #[Serializer\Type(CustomerOrderPaymentInfo::class)]
    #[Serializer\SerializedName('paymentInfo')]
    #[Serializer\Accessor(getter: 'getPaymentInfo', setter: 'setPaymentInfo')]
    protected ?CustomerOrderPaymentInfo $paymentInfo = null;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('paymentModuleCode')]
    #[Serializer\Accessor(getter: 'getPaymentModuleCode', setter: 'setPaymentModuleCode')]
    protected string $paymentModuleCode = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('paymentStatus')]
    #[Serializer\Accessor(getter: 'getPaymentStatus', setter: 'setPaymentStatus')]
    protected string $paymentStatus = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('pui')]
    #[Serializer\Accessor(getter: 'getPui', setter: 'setPui')]
    protected string $pui = '';

    /** @var CustomerOrderShippingAddress|null Shipping address */
    #[Serializer\Type(CustomerOrderShippingAddress::class)]
    #[Serializer\SerializedName('shippingAddress')]
    #[Serializer\Accessor(getter: 'getShippingAddress', setter: 'setShippingAddress')]
    protected ?CustomerOrderShippingAddress $shippingAddress = null;

    /** @var \DateTimeInterface|null Shipping date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('shippingDate')]
    #[Serializer\Accessor(getter: 'getShippingDate', setter: 'setShippingDate')]
    protected ?\DateTimeInterface $shippingDate = null;

    /** @var string Additional shipping info */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('shippingInfo')]
    #[Serializer\Accessor(getter: 'getShippingInfo', setter: 'setShippingInfo')]
    protected string $shippingInfo = '';

    /** @var Identity Optional reference to customer. */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('shippingMethodId')]
    #[Serializer\Accessor(getter: 'getShippingMethodId', setter: 'setShippingMethodId')]
    protected Identity $shippingMethodId;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('shippingMethodName')]
    #[Serializer\Accessor(getter: 'getShippingMethodName', setter: 'setShippingMethodName')]
    protected string $shippingMethodName = '';

    /** @var string Shipping status */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('status')]
    #[Serializer\Accessor(getter: 'getStatus', setter: 'setStatus')]
    protected string $status = '';

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('totalSum')]
    #[Serializer\Accessor(getter: 'getTotalSum', setter: 'setTotalSum')]
    protected float $totalSum = 0.0;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('totalSumGross')]
    #[Serializer\Accessor(getter: 'getTotalSumGross', setter: 'setTotalSumGross')]
    protected float $totalSumGross = 0.0;

    /** @var KeyValueAttribute[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\KeyValueAttribute>')]
    #[Serializer\SerializedName('attributes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $attributes = [];

    /** @var CustomerOrderItem[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CustomerOrderItem>')]
    #[Serializer\SerializedName('items')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $items = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id               = new Identity();
        $this->customerId       = new Identity();
        $this->shippingMethodId = new Identity();
    }

    /**
     * @return Identity Optional reference to customer.
     */
    public function getCustomerId(): Identity
    {
        return $this->customerId;
    }

    /**
     * @param Identity $customerId Optional reference to customer.
     *
     * @return $this
     */
    public function setCustomerId(Identity $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return Identity Unique customerOrder id
     */
    public function getId(): Identity
    {
        return $this->id;
    }

    /**
     * @param Identity $identity Unique customerOrder id
     *
     * @return $this
     */
    public function setId(Identity $identity): self
    {
        $this->id = $identity;

        return $this;
    }

    /**
     * @return CustomerOrderBillingAddress|null Billing address
     */
    public function getBillingAddress(): ?CustomerOrderBillingAddress
    {
        return $this->billingAddress;
    }

    /**
     * @param CustomerOrderBillingAddress|null $billingAddress Billing address
     *
     * @return $this
     */
    public function setBillingAddress(?CustomerOrderBillingAddress $billingAddress = null): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getCarrierName(): string
    {
        return $this->carrierName;
    }

    /**
     * @param string $carrierName
     *
     * @return $this
     */
    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    /**
     * @return \DateTimeInterface Date of creation
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param DateTimeInterface|null $creationDate Date of creation
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
    public function getCurrencyIso(): string
    {
        return $this->currencyIso;
    }

    /**
     * @param string $currencyIso
     *
     * @return $this
     */
    public function setCurrencyIso(string $currencyIso): self
    {
        $this->currencyIso = $currencyIso;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerNote(): string
    {
        return $this->customerNote;
    }

    /**
     * @param string $customerNote
     *
     * @return $this
     */
    public function setCustomerNote(string $customerNote): self
    {
        $this->customerNote = $customerNote;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEstimatedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * @param DateTimeInterface|null $estimatedDeliveryDate
     *
     * @return $this
     */
    public function setEstimatedDeliveryDate(?\DateTimeInterface $estimatedDeliveryDate = null): self
    {
        $this->estimatedDeliveryDate = $estimatedDeliveryDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string Optional order number (usually set by ERP System later)
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber Optional order number (usually set by ERP System later)
     *
     * @return $this
     */
    public function setOrderNumber(string $orderNumber): self
    {
        $this->setIdentificationStringBySubject('orderNumber', \sprintf('Order number = %s', $orderNumber));
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return \DateTimeInterface Payment date
     */
    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    /**
     * @param \DateTimeInterface|null $paymentDate Payment date
     *
     * @return $this
     */
    public function setPaymentDate(?\DateTimeInterface $paymentDate = null): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return CustomerOrderPaymentInfo
     */
    public function getPaymentInfo(): ?CustomerOrderPaymentInfo
    {
        return $this->paymentInfo;
    }

    /**
     * @param CustomerOrderPaymentInfo|null $paymentInfo
     *
     * @return $this
     */
    public function setPaymentInfo(?CustomerOrderPaymentInfo $paymentInfo = null): self
    {
        $this->paymentInfo = $paymentInfo;

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
     * @return string
     */
    public function getPui(): string
    {
        return $this->pui;
    }

    /**
     * @param string $pui
     *
     * @return $this
     */
    public function setPui(string $pui): self
    {
        $this->pui = $pui;

        return $this;
    }

    /**
     * @return CustomerOrderShippingAddress Shipping address
     */
    public function getShippingAddress(): ?CustomerOrderShippingAddress
    {
        return $this->shippingAddress;
    }

    /**
     * @param CustomerOrderShippingAddress|null $shippingAddress Shipping address
     *
     * @return $this
     */
    public function setShippingAddress(?CustomerOrderShippingAddress $shippingAddress = null): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null Shipping date
     */
    public function getShippingDate(): ?\DateTimeInterface
    {
        return $this->shippingDate;
    }

    /**
     * @param DateTimeInterface|null $shippingDate Shipping date
     *
     * @return $this
     */
    public function setShippingDate(?\DateTimeInterface $shippingDate = null): self
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    /**
     * @return string Additional shipping info
     */
    public function getShippingInfo(): string
    {
        return $this->shippingInfo;
    }

    /**
     * @param string $shippingInfo Additional shipping info
     *
     * @return $this
     */
    public function setShippingInfo(string $shippingInfo): self
    {
        $this->shippingInfo = $shippingInfo;

        return $this;
    }

    /**
     * @return Identity Optional reference to shipping method.
     */
    public function getShippingMethodId(): Identity
    {
        return $this->shippingMethodId;
    }

    /**
     * @param Identity $shippingMethodId Optional reference to customer.
     *
     * @return $this
     */
    public function setShippingMethodId(Identity $shippingMethodId): self
    {
        $this->shippingMethodId = $shippingMethodId;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingMethodName(): string
    {
        return $this->shippingMethodName;
    }

    /**
     * @param string $shippingMethodName
     *
     * @return $this
     */
    public function setShippingMethodName(string $shippingMethodName): self
    {
        $this->shippingMethodName = $shippingMethodName;

        return $this;
    }

    /**
     * @return string Shipping status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status Shipping status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

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
     * @return float
     */
    public function getTotalSumGross(): float
    {
        return $this->totalSumGross;
    }

    /**
     * @param float $totalSumGross
     *
     * @return $this
     */
    public function setTotalSumGross(float $totalSumGross): self
    {
        $this->totalSumGross = $totalSumGross;

        return $this;
    }

    /**
     * @param KeyValueAttribute $attribute
     *
     * @return $this
     */
    public function addAttribute(KeyValueAttribute $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * @return KeyValueAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param KeyValueAttribute ...$attributes
     *
     * @return $this
     */
    public function setAttributes(KeyValueAttribute ...$attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAttributes(): self
    {
        $this->attributes = [];

        return $this;
    }

    /**
     * @phpstan-param CustomerOrderItem $item
     *
     * @param AbstractModel $item
     *
     * @return $this
     */
    public function addItem(AbstractModel $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return CustomerOrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @phpstan-param CustomerOrderItem ...$items
     *
     * @param AbstractModel ...$items
     *
     * @return $this
     */
    public function setItems(AbstractModel ...$items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clearItems(): self
    {
        $this->items = [];

        return $this;
    }
}

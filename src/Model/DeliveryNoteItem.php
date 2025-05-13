<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class DeliveryNoteItem extends AbstractIdentity
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerOrderItemId')]
    #[Serializer\Accessor(getter: 'getCustomerOrderItemId', setter: 'setCustomerOrderItemId')]
    protected Identity $customerOrderItemId;

    /** @var Identity Reference to product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('quantity')]
    #[Serializer\Accessor(getter: 'getQuantity', setter: 'setQuantity')]
    protected float $quantity = 0.0;

    /** @var DeliveryNoteItemInfo[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\DeliveryNoteItemInfo>')]
    #[Serializer\SerializedName('info')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $info = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->productId           = new Identity();
        $this->customerOrderItemId = new Identity();
    }

    /**
     * @return Identity
     */
    public function getCustomerOrderItemId(): Identity
    {
        return $this->customerOrderItemId;
    }

    /**
     * @param Identity $customerOrderItemId
     *
     * @return $this
     */
    public function setCustomerOrderItemId(Identity $customerOrderItemId): self
    {
        $this->customerOrderItemId = $customerOrderItemId;

        return $this;
    }

    /**
     * @return Identity Reference to product
     */
    public function getProductId(): Identity
    {
        return $this->productId;
    }

    /**
     * @param Identity $productId Reference to product
     *
     * @return $this
     */
    public function setProductId(Identity $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     *
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param DeliveryNoteItemInfo $info
     *
     * @return $this
     */
    public function addInfo(DeliveryNoteItemInfo $info): self
    {
        $this->info[] = $info;

        return $this;
    }

    /**
     * @return DeliveryNoteItemInfo[]
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @param DeliveryNoteItemInfo ...$info
     *
     * @return $this
     */
    public function setInfo(DeliveryNoteItemInfo ...$info): self
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearInfo(): self
    {
        $this->info = [];

        return $this;
    }
}

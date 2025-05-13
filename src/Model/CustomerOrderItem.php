<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Order item in customer order.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CustomerOrderItem extends AbstractIdentity
{
    public const TYPE_DISCOUNT = 'discount';

    public const TYPE_PRODUCT = 'product';

    public const TYPE_SHIPPING = 'shipping';

    public const TYPE_SURCHARGE = 'surcharge';

    public const TYPE_COUPON = 'coupon';

    /** @var Identity Optional reference to configItemId (if item is part of a configurable item) */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('configItemId')]
    #[Serializer\Accessor(getter: 'getConfigItemId', setter: 'setConfigItemId')]
    protected Identity $configItemId;

    /** @var Identity Reference to product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;

    /** @var string Order item name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var double Price (net) */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('price')]
    #[Serializer\Accessor(getter: 'getPrice', setter: 'setPrice')]
    protected float $price = 0.0;

    /** @var double Price (gross) */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('priceGross')]
    #[Serializer\Accessor(getter: 'getPriceGross', setter: 'setPriceGross')]
    protected float $priceGross = 0.0;

    /** @var double Quantity purchased */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('quantity')]
    #[Serializer\Accessor(getter: 'getQuantity', setter: 'setQuantity')]
    protected float $quantity = 0.0;

    /** @var string Stock keeping Unit (unique item identifier) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('sku')]
    #[Serializer\Accessor(getter: 'getSku', setter: 'setSku')]
    protected string $sku = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected string $type = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    /** @var string Optional unique Hashsum (if item is part of configurable item */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('unique')]
    #[Serializer\Accessor(getter: 'getUnique', setter: 'setUnique')]
    protected string $unique = '';

    /** @var double Value added tax */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('vat')]
    #[Serializer\Accessor(getter: 'getVat', setter: 'setVat')]
    protected float $vat = 0.0;

    /** @var CustomerOrderItemVariation[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CustomerOrderItemVariation>')]
    #[Serializer\SerializedName('variations')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $variations = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->productId    = new Identity();
        $this->configItemId = new Identity();
    }

    /**
     * @return Identity Optional reference to configItemId (if item is part of a configurable item)
     */
    public function getConfigItemId(): Identity
    {
        return $this->configItemId;
    }

    /**
     * @param Identity $configItemId Optional reference to configItemId (if item is part of a configurable item)
     *
     * @return $this
     */
    public function setConfigItemId(Identity $configItemId): self
    {
        $this->configItemId = $configItemId;

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
     * @return string Order item name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Order item name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float Price (net)
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price Price (net)
     *
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float PriceGross (gross)
     */
    public function getPriceGross(): float
    {
        return $this->priceGross;
    }

    /**
     * @param float $priceGross Price (gross)
     *
     * @return $this
     */
    public function setPriceGross(float $priceGross): self
    {
        $this->priceGross = $priceGross;

        return $this;
    }

    /**
     * @return float Quantity purchased
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity Quantity purchased
     *
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string Stock keeping Unit (unique item identifier)
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku Stock keeping Unit (unique item identifier)
     *
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
     * @return string Optional unique Hashsum (if item is part of configurable item
     */
    public function getUnique(): string
    {
        return $this->unique;
    }

    /**
     * @param string $unique Optional unique Hashsum (if item is part of configurable item
     *
     * @return $this
     */
    public function setUnique(string $unique): self
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * @return float Value added tax
     */
    public function getVat(): float
    {
        return $this->vat;
    }

    /**
     * @param float $vat Value added tax
     *
     * @return $this
     */
    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @param CustomerOrderItemVariation $variation
     *
     * @return $this
     */
    public function addVariation(CustomerOrderItemVariation $variation): self
    {
        $this->variations[] = $variation;

        return $this;
    }

    /**
     * @return CustomerOrderItemVariation[]
     */
    public function getVariations(): array
    {
        return $this->variations;
    }

    /**
     * @param CustomerOrderItemVariation ...$variations
     *
     * @return $this
     */
    public function setVariations(CustomerOrderItemVariation ...$variations): self
    {
        $this->variations = $variations;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearVariations(): self
    {
        $this->variations = [];

        return $this;
    }
}

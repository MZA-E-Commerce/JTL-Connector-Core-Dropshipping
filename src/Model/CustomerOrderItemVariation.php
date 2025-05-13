<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * customer order item variation
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CustomerOrderItemVariation extends AbstractIdentity
{
    /** @var Identity Reference to productVariation */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productVariationId')]
    #[Serializer\Accessor(getter: 'getProductVariationId', setter: 'setProductVariationId')]
    protected Identity $productVariationId;

    /** @var Identity Reference to productVariationValue */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productVariationValueId')]
    #[Serializer\Accessor(getter: 'getProductVariationValueId', setter: 'setProductVariationValueId')]
    protected Identity $productVariationValueId;

    /** @var string Optional custom text value for variation */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('freeField')]
    #[Serializer\Accessor(getter: 'getFreeField', setter: 'setFreeField')]
    protected string $freeField = '';

    /** @var string Variation name e.g. 'color' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('productVariationName')]
    #[Serializer\Accessor(getter: 'getProductVariationName', setter: 'setProductVariationName')]
    protected string $productVariationName = '';


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('surcharge')]
    #[Serializer\Accessor(getter: 'getSurcharge', setter: 'setSurcharge')]
    protected float $surcharge = 0.0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('valueName')]
    #[Serializer\Accessor(getter: 'getValueName', setter: 'setValueName')]
    protected string $valueName = '';

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->productVariationValueId = new Identity();
        $this->productVariationId      = new Identity();
    }

    /**
     * @return Identity Reference to productVariation
     */
    public function getProductVariationId(): Identity
    {
        return $this->productVariationId;
    }

    /**
     * @param Identity $productVariationId Reference to productVariation
     *
     * @return $this
     */
    public function setProductVariationId(Identity $productVariationId): self
    {
        $this->productVariationId = $productVariationId;

        return $this;
    }

    /**
     * @return Identity Reference to productVariationValue
     */
    public function getProductVariationValueId(): Identity
    {
        return $this->productVariationValueId;
    }

    /**
     * @param Identity $productVariationValueId Reference to productVariationValue
     *
     * @return $this
     */
    public function setProductVariationValueId(Identity $productVariationValueId): self
    {
        $this->productVariationValueId = $productVariationValueId;

        return $this;
    }

    /**
     * @return string Optional custom text value for variation
     */
    public function getFreeField(): string
    {
        return $this->freeField;
    }

    /**
     * @param string $freeField Optional custom text value for variation
     *
     * @return $this
     */
    public function setFreeField(string $freeField): self
    {
        $this->freeField = $freeField;

        return $this;
    }

    /**
     * @return string Variation name e.g. 'color'
     */
    public function getProductVariationName(): string
    {
        return $this->productVariationName;
    }

    /**
     * @param string $productVariationName Variation name e.g. 'color'
     *
     * @return $this
     */
    public function setProductVariationName(string $productVariationName): self
    {
        $this->productVariationName = $productVariationName;

        return $this;
    }

    /**
     * @return float
     */
    public function getSurcharge(): float
    {
        return $this->surcharge;
    }

    /**
     * @param float $surcharge
     *
     * @return $this
     */
    public function setSurcharge(float $surcharge): self
    {
        $this->surcharge = $surcharge;

        return $this;
    }

    /**
     * @return string
     */
    public function getValueName(): string
    {
        return $this->valueName;
    }

    /**
     * @param string $valueName
     *
     * @return $this
     */
    public function setValueName(string $valueName): self
    {
        $this->valueName = $valueName;

        return $this;
    }
}

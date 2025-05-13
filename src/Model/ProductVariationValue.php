<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Product variation value model. Each product defines its own variations and variation values.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductVariationValue extends AbstractIdentity
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('ean')]
    #[Serializer\Accessor(getter: 'getEan', setter: 'setEan')]
    protected string $ean = '';

    /** @var double Optional variation extra weight */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('extraWeight')]
    #[Serializer\Accessor(getter: 'getExtraWeight', setter: 'setExtraWeight')]
    protected float $extraWeight = 0.0;

    /** @var string Optional Stock Keeping Unit */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('sku')]
    #[Serializer\Accessor(getter: 'getSku', setter: 'setSku')]
    protected string $sku = '';

    /** @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var double Optional stock level */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('stockLevel')]
    #[Serializer\Accessor(getter: 'getStockLevel', setter: 'setStockLevel')]
    protected float $stockLevel = 0.0;

    /** @var ProductVariationValueExtraCharge[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationValueExtraCharge>')]
    #[Serializer\SerializedName('extraCharges')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $extraCharges = [];

    /** @var ProductVariationValueI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationValueI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /** @var ProductVariationValueInvisibility[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationValueInvisibility>')]
    #[Serializer\SerializedName('invisibilities')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $invisibilities = [];

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     *
     * @return $this
     */
    public function setEan(string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * @return float Optional variation extra weight
     */
    public function getExtraWeight(): float
    {
        return $this->extraWeight;
    }

    /**
     * @param float $extraWeight Optional variation extra weight
     *
     * @return $this
     */
    public function setExtraWeight(float $extraWeight): self
    {
        $this->extraWeight = $extraWeight;

        return $this;
    }

    /**
     * @return string Optional Stock Keeping Unit
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku Optional Stock Keeping Unit
     *
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int Optional sort number
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort number
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return float Optional stock level
     */
    public function getStockLevel(): float
    {
        return $this->stockLevel;
    }

    /**
     * @param float $stockLevel Optional stock level
     *
     * @return $this
     */
    public function setStockLevel(float $stockLevel): self
    {
        $this->stockLevel = $stockLevel;

        return $this;
    }

    /**
     * @param ProductVariationValueExtraCharge $extraCharge
     *
     * @return $this
     */
    public function addExtraCharge(
        ProductVariationValueExtraCharge $extraCharge
    ): self {
        $this->extraCharges[] = $extraCharge;

        return $this;
    }

    /**
     * @return ProductVariationValueExtraCharge[]
     */
    public function getExtraCharges(): array
    {
        return $this->extraCharges;
    }

    /**
     * @param ProductVariationValueExtraCharge ...$extraCharges
     *
     * @return $this
     */
    public function setExtraCharges(ProductVariationValueExtraCharge ...$extraCharges): self
    {
        $this->extraCharges = $extraCharges;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearExtraCharges(): self
    {
        $this->extraCharges = [];

        return $this;
    }

    /**
     * @param ProductVariationValueI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductVariationValueI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ProductVariationValueI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductVariationValueI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductVariationValueI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearI18ns(): self
    {
        $this->i18ns = [];

        return $this;
    }

    /**
     * @param ProductVariationValueInvisibility $invisibility
     *
     * @return $this
     */
    public function addInvisibility(
        ProductVariationValueInvisibility $invisibility
    ): self {
        $this->invisibilities[] = $invisibility;

        return $this;
    }

    /**
     * @return ProductVariationValueInvisibility[]
     */
    public function getInvisibilities(): array
    {
        return $this->invisibilities;
    }

    /**
     * @param ProductVariationValueInvisibility[] $invisibilities
     *
     * @return $this
     */
    public function setInvisibilities(array $invisibilities): self
    {
        $this->invisibilities = $invisibilities;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearInvisibilities(): self
    {
        $this->invisibilities = [];

        return $this;
    }
}

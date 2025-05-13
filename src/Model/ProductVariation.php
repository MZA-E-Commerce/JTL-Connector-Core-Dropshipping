<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * ProductVariation Model. Each product defines its own variations,
 * that means  variations are not global  in contrast to specifics.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductVariation extends AbstractIdentity
{
    public const TYPE_RADIO                = 'radio';
    public const TYPE_SELECT               = 'select';
    public const TYPE_TEXTBOX              = 'textbox';
    public const TYPE_FREE_TEXT            = 'freetext';
    public const TYPE_FREE_TEXT_OBLIGATORY = 'obligatory_freetext';
    public const TYPE_IMAGE_SWATCHES       = 'image_swatches';

    /** @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected string $type = '';

    /** @var ProductVariationI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /** @var ProductVariationInvisibility[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationInvisibility>')]
    #[Serializer\SerializedName('invisibilities')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $invisibilities = [];

    /** @var ProductVariationValue[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariationValue>')]
    #[Serializer\SerializedName('values')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $values = [];

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
     * @param ProductVariationI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductVariationI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ProductVariationI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductVariationI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductVariationI18n ...$i18ns): self
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
     * @param ProductVariationInvisibility $invisibility
     *
     * @return $this
     */
    public function addInvisibility(ProductVariationInvisibility $invisibility): self
    {
        $this->invisibilities[] = $invisibility;

        return $this;
    }

    /**
     * @return ProductVariationInvisibility[]
     */
    public function getInvisibilities(): array
    {
        return $this->invisibilities;
    }

    /**
     * @param ProductVariationInvisibility ...$invisibilities
     *
     * @return $this
     */
    public function setInvisibilities(ProductVariationInvisibility ...$invisibilities): self
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

    /**
     * @param ProductVariationValue $value
     *
     * @return $this
     */
    public function addValue(ProductVariationValue $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * @return ProductVariationValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param ProductVariationValue ...$values
     *
     * @return $this
     */
    public function setValues(ProductVariationValue ...$values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearValues(): self
    {
        $this->values = [];

        return $this;
    }
}

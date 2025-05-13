<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Media file model.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductMediaFile extends AbstractIdentity
{
    /** @var string Optional media file category name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('mediaFileCategory')]
    #[Serializer\Accessor(getter: 'getMediaFileCategory', setter: 'setMediaFileCategory')]
    protected string $mediaFileCategory = '';

    /** @var string File path */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('path')]
    #[Serializer\Accessor(getter: 'getPath', setter: 'setPath')]
    protected string $path = '';

    /** @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var string Media file type e.g. 'pdf' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected string $type = '';

    /** @var string Complete URL */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('url')]
    #[Serializer\Accessor(getter: 'getUrl', setter: 'setUrl')]
    protected string $url = '';

    /** @var ProductMediaFileAttr[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductMediaFileAttr>')]
    #[Serializer\SerializedName('attributes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $attributes = [];

    /** @var ProductMediaFileI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductMediaFileI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return string Optional media file category name
     */
    public function getMediaFileCategory(): string
    {
        return $this->mediaFileCategory;
    }

    /**
     * @param string $mediaFileCategory Optional media file category name
     *
     * @return $this
     */
    public function setMediaFileCategory(string $mediaFileCategory): self
    {
        $this->mediaFileCategory = $mediaFileCategory;

        return $this;
    }

    /**
     * @return string File path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path File path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

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
     * @return string Media file type e.g. 'pdf'
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type Media file type e.g. 'pdf'
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string Complete URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url Complete URL
     *
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param ProductMediaFileAttr $attribute
     *
     * @return $this
     */
    public function addAttribute(ProductMediaFileAttr $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * @return ProductMediaFileAttr[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param ProductMediaFileAttr ...$attributes
     *
     * @return $this
     */
    public function setAttributes(ProductMediaFileAttr ...$attributes): self
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
     * @param ProductMediaFileI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductMediaFileI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ProductMediaFileI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductMediaFileI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductMediaFileI18n ...$i18ns): self
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
}

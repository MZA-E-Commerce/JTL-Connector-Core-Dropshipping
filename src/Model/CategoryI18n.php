<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Localized category properties. localeName, categoryId and a localized name must be set.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CategoryI18n extends AbstractI18n
{
    /** @var string Optional localized Long Description */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('description')]
    #[Serializer\Accessor(getter: 'getDescription', setter: 'setDescription')]
    protected string $description = '';

    /** @var string Optional localized  short description used for meta tag description */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('metaDescription')]
    #[Serializer\Accessor(getter: 'getMetaDescription', setter: 'setMetaDescription')]
    protected string $metaDescription = '';

    /** @var string Optional localized meta tag keywords value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('metaKeywords')]
    #[Serializer\Accessor(getter: 'getMetaKeywords', setter: 'setMetaKeywords')]
    protected string $metaKeywords = '';

    /** @var string Localized category name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var string Optional localized title tag value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('titleTag')]
    #[Serializer\Accessor(getter: 'getTitleTag', setter: 'setTitleTag')]
    protected string $titleTag = '';

    /** @var string Optional localized category URL */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('urlPath')]
    #[Serializer\Accessor(getter: 'getUrlPath', setter: 'setUrlPath')]
    protected string $urlPath = '';

    /**
     * @return string Optional localized Long Description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Optional localized Long Description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string Optional localized  short description used for meta tag description
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription Optional localized  short description used for meta tag description
     *
     * @return $this
     */
    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string Optional localized meta tag keywords value
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords Optional localized meta tag keywords value
     *
     * @return $this
     */
    public function setMetaKeywords(string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string Localized category name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Localized category name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string Optional localized title tag value
     */
    public function getTitleTag(): string
    {
        return $this->titleTag;
    }

    /**
     * @param string $titleTag Optional localized title tag value
     *
     * @return $this
     */
    public function setTitleTag(string $titleTag): self
    {
        $this->titleTag = $titleTag;

        return $this;
    }

    /**
     * @return string Optional localized category URL
     */
    public function getUrlPath(): string
    {
        return $this->urlPath;
    }

    /**
     * @param string $urlPath Optional localized category URL
     *
     * @return $this
     */
    public function setUrlPath(string $urlPath): self
    {
        $this->urlPath = $urlPath;

        return $this;
    }
}

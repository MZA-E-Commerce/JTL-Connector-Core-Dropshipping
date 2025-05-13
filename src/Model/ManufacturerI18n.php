<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Locale specific text and meta-information for manufacturer.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ManufacturerI18n extends AbstractI18n
{
    /** @var string Optional manufacturer description (HTML) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('description')]
    #[Serializer\Accessor(getter: 'getDescription', setter: 'setDescription')]
    protected string $description = '';

    /** @var string Optional meta description tag value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('metaDescription')]
    #[Serializer\Accessor(getter: 'getMetaDescription', setter: 'setMetaDescription')]
    protected string $metaDescription = '';

    /** @var string Optional meta keywords tag value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('metaKeywords')]
    #[Serializer\Accessor(getter: 'getMetaKeywords', setter: 'setMetaKeywords')]
    protected string $metaKeywords = '';

    /** @var string Optional title tag value */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('titleTag')]
    #[Serializer\Accessor(getter: 'getTitleTag', setter: 'setTitleTag')]
    protected string $titleTag = '';

    /**
     * @return string Optional manufacturer description (HTML)
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Optional manufacturer description (HTML)
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string Optional meta description tag value
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription Optional meta description tag value
     *
     * @return $this
     */
    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string Optional meta keywords tag value
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords Optional meta keywords tag value
     *
     * @return $this
     */
    public function setMetaKeywords(string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string Optional title tag value
     */
    public function getTitleTag(): string
    {
        return $this->titleTag;
    }

    /**
     * @param string $titleTag Optional title tag value
     *
     * @return $this
     */
    public function setTitleTag(string $titleTag): self
    {
        $this->titleTag = $titleTag;

        return $this;
    }
}

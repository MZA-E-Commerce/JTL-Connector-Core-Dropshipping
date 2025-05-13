<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Manufacturer / brand properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Manufacturer extends AbstractIdentity
{
    /** @var string Manufacturer (brand) name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var string Optional url path e.g. 'Products-manufactured-by-X' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('urlPath')]
    #[Serializer\Accessor(getter: 'getUrlPath', setter: 'setUrlPath')]
    protected string $urlPath = '';

    /** @var string Optional manufacturer website URL */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('websiteUrl')]
    #[Serializer\Accessor(getter: 'getWebsiteUrl', setter: 'setWebsiteUrl')]
    protected string $websiteUrl = '';

    /** @var ManufacturerI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ManufacturerI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return string Manufacturer (brand) name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Manufacturer (brand) name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->setIdentificationStringBySubject('name', \sprintf('Name = %s', $name));
        $this->name = $name;

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
     * @return string Optional url path e.g. 'Products-manufactured-by-X'
     */
    public function getUrlPath(): string
    {
        return $this->urlPath;
    }

    /**
     * @param string $urlPath Optional url path e.g. 'Products-manufactured-by-X'
     *
     * @return $this
     */
    public function setUrlPath(string $urlPath): self
    {
        $this->urlPath = $urlPath;

        return $this;
    }

    /**
     * @return string Optional manufacturer website URL
     */
    public function getWebsiteUrl(): string
    {
        return $this->websiteUrl;
    }

    /**
     * @param string $websiteUrl Optional manufacturer website URL
     *
     * @return $this
     */
    public function setWebsiteUrl(string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * @param ManufacturerI18n $i18n
     *
     * @return Manufacturer
     */
    public function addI18n(ManufacturerI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ManufacturerI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ManufacturerI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ManufacturerI18n ...$i18ns): self
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

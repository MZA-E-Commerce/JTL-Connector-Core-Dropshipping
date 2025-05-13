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
class ProductFileDownload extends AbstractModel
{
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('maxDays')]
    #[Serializer\Accessor(getter: 'getMaxDays', setter: 'setMaxDays')]
    protected int $maxDays = 0;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('maxDownloads')]
    #[Serializer\Accessor(getter: 'getMaxDownloads', setter: 'setMaxDownloads')]
    protected int $maxDownloads = 0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('path')]
    #[Serializer\Accessor(getter: 'getPath', setter: 'setPath')]
    protected string $path = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('previewPath')]
    #[Serializer\Accessor(getter: 'getPreviewPath', setter: 'setPreviewPath')]
    protected string $previewPath = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var ProductFileDownloadI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductFileDownloadI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * @return \DateTimeInterface
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface $creationDate
     *
     * @return $this
     */
    public function setCreationDate(?\DateTimeInterface $creationDate = null): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxDays(): int
    {
        return $this->maxDays;
    }

    /**
     * @param int $maxDays
     *
     * @return $this
     */
    public function setMaxDays(int $maxDays): self
    {
        $this->maxDays = $maxDays;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxDownloads(): int
    {
        return $this->maxDownloads;
    }

    /**
     * @param int $maxDownloads
     *
     * @return $this
     */
    public function setMaxDownloads(int $maxDownloads): self
    {
        $this->maxDownloads = $maxDownloads;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreviewPath(): string
    {
        return $this->previewPath;
    }

    /**
     * @param string $previewPath
     *
     * @return $this
     */
    public function setPreviewPath(string $previewPath): self
    {
        $this->previewPath = $previewPath;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param ProductFileDownloadI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductFileDownloadI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ProductFileDownloadI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductFileDownloadI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductFileDownloadI18n ...$i18ns): self
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

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * File upload properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class FileUpload extends AbstractIdentity
{
    /** @var Identity Reference to product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('fileType')]
    #[Serializer\Accessor(getter: 'getFileType', setter: 'setFileType')]
    protected string $fileType = '';

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isRequired')]
    #[Serializer\Accessor(getter: 'getIsRequired', setter: 'setIsRequired')]
    protected bool $isRequired = false;

    /** @var FileUploadI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\FileUploadI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->productId = new Identity();
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
     * @return string
     */
    public function getFileType(): string
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     *
     * @return $this
     */
    public function setFileType(string $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     *
     * @return $this
     */
    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * @param FileUploadI18n $i18n
     *
     * @return $this
     */
    public function addI18n(FileUploadI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return FileUploadI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param FileUploadI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(FileUploadI18n ...$i18ns): self
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

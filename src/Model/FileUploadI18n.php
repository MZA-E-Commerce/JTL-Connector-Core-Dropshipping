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
class FileUploadI18n extends AbstractI18n
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('description')]
    #[Serializer\Accessor(getter: 'getDescription', setter: 'setDescription')]
    protected string $description = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('fileUploadId')]
    #[Serializer\Accessor(getter: 'getFileUploadId', setter: 'setFileUploadId')]
    protected int $fileUploadId = 0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getFileUploadId(): int
    {
        return $this->fileUploadId;
    }

    /**
     * @param int $fileUploadId
     *
     * @return $this
     */
    public function setFileUploadId(int $fileUploadId): self
    {
        $this->fileUploadId = $fileUploadId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

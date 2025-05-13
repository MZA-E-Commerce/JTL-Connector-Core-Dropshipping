<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Definition\Model;
use Jtl\Connector\Core\Exception\DefinitionException;
use RuntimeException;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
#[Serializer\Discriminator(
    field   : 'relationType',
    map     : [
        'category' => 'Jtl\Connector\Core\Model\CategoryImage',
        'configGroup' => 'Jtl\Connector\Core\Model\ConfigGroupImage',
        'manufacturer' => 'Jtl\Connector\Core\Model\ManufacturerImage',
        'product' => 'Jtl\Connector\Core\Model\ProductImage',
        'productVariationValue' => 'Jtl\Connector\Core\Model\ProductVariationValueImage',
        'specific' => 'Jtl\Connector\Core\Model\SpecificImage',
        'specificValue' => 'Jtl\Connector\Core\Model\SpecificValueImage'
    ],
    disabled: false
)]
abstract class AbstractImage extends AbstractIdentity
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('filename')]
    #[Serializer\Accessor(getter: 'getFilenameSafe', setter: 'setFilename')]
    protected string $filename = '';

    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('foreignKey')]
    #[Serializer\Accessor(getter: 'getForeignKey', setter: 'setForeignKey')]
    protected Identity $foreignKey;

    /** @var ImageI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ImageI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('remoteUrl')]
    #[Serializer\Accessor(getter: 'getRemoteUrl', setter: 'setRemoteUrl')]
    protected string $remoteUrl = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 1;

    /** @var \ReflectionClass<self> */
    #[Serializer\Exclude]
    protected \ReflectionClass $reflectionClass;

    /**
     * AbstractImage constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        $this->reflectionClass = new \ReflectionClass($this);
        parent::__construct($endpoint, $host);
        $this->foreignKey = new Identity();
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        $dotPos = \strrpos($this->filename, '.');
        if ($dotPos !== false) {
            return \substr($this->filename, $dotPos + 1);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getFilenameSafe(): string
    {
        $path = \explode('/', $this->filename);
        return \end($path);
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getForeignKey(): Identity
    {
        return $this->foreignKey;
    }

    /**
     * @param Identity $foreignKey
     *
     * @return $this
     */
    public function setForeignKey(Identity $foreignKey): self
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * @param ImageI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ImageI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

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
     * @return ImageI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ImageI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ImageI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

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

    /**
     * @return string
     */
    public function getRemoteUrl(): string
    {
        return $this->remoteUrl;
    }

    /**
     * @param string $remoteUrl
     *
     * @return $this
     */
    public function setRemoteUrl(string $remoteUrl): self
    {
        $this->remoteUrl = $remoteUrl;

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
     * @return string[]
     * @throws DefinitionException
     * @throws RuntimeException
     */
    public function getIdentificationStrings(): array
    {
        $this->setIdentificationStringBySubject(
            'foreignKey',
            \sprintf(
                'Related type %s (JTL-Wawi PK = %d)',
                \ucfirst($this->getRelationType()),
                $this->foreignKey->getHost()
            )
        );

        return parent::getIdentificationStrings();
    }

    /**
     * @return string
     * @throws DefinitionException
     * @throws RuntimeException
     */
    public function getRelationType(): string
    {
        $modelName = $this->reflectionClass->getShortName();
        $imagePos  = \strpos($modelName, 'Image');
        if ($imagePos === false) {
            throw new \RuntimeException('$imagePos must not be false!');
        }

        return Model::getRelationType(\substr($modelName, 0, $imagePos));
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Checksum\ChecksumInterface;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Checksum extends AbstractModel implements ChecksumInterface
{
    public const TYPE_VARIATION = 1;

    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('foreignKey')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected Identity $foreignKey;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('endpoint')]
    #[Serializer\Accessor(getter: 'getEndpoint', setter: 'setEndpoint')]
    protected string $endpoint = '';

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('hasChanged')]
    #[Serializer\Accessor(getter: 'getHasChanged', setter: 'setHasChanged')]
    protected bool $hasChanged = false;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('host')]
    #[Serializer\Accessor(getter: 'getHost', setter: 'setHost')]
    protected string $host = '';

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected int $type = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->foreignKey = new Identity();
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
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     *
     * @return $this
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasChanged(): bool
    {
        return $this->hasChanged;
    }

    /**
     * @param bool $hasChanged
     *
     * @return $this
     */
    public function setHasChanged(bool $hasChanged): self
    {
        $this->hasChanged = $hasChanged;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}

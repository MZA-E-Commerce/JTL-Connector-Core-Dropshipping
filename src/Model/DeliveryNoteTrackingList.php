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
class DeliveryNoteTrackingList extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('name')]
    #[Serializer\Accessor(getter: 'getName', setter: 'setName')]
    protected string $name = '';

    /** @var string[] */
    #[Serializer\Type('array<string>')]
    #[Serializer\SerializedName('codes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $codes = [];

    /** @var string[] */
    #[Serializer\Type('array<string>')]
    #[Serializer\SerializedName('trackingURLs')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $trackingURLs = [];

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
     * @param string $code
     *
     * @return $this
     */
    public function addCode(string $code): self
    {
        $this->codes[] = $code;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }

    /**
     * @param string ...$codes
     *
     * @return $this
     */
    public function setCodes(string ...$codes): self
    {
        $this->codes = $codes;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCodes(): self
    {
        $this->codes = [];

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTrackingURLs(): array
    {
        return $this->trackingURLs;
    }

    /**
     * @param string ...$trackingURLs
     *
     * @return $this
     */
    public function setTrackingURLs(string ...$trackingURLs): self
    {
        $this->trackingURLs = $trackingURLs;

        return $this;
    }

    /**
     * @param string $trackingURL
     *
     * @return $this
     */
    public function addTrackingURL(string $trackingURL): self
    {
        $this->trackingURLs[] = $trackingURL;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearTrackingURLs(): self
    {
        $this->trackingURLs = [];

        return $this;
    }
}

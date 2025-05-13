<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;

/**
 * Shipment Model with reference to a deliveryNote
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Shipment extends AbstractIdentity
{
    /** @var Identity Reference to deliveryNote */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('deliveryNoteId')]
    #[Serializer\Accessor(getter: 'getDeliveryNoteId', setter: 'setDeliveryNoteId')]
    protected Identity $deliveryNoteId;

    /** @var string Carrier name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('carrierName')]
    #[Serializer\Accessor(getter: 'getCarrierName', setter: 'setCarrierName')]
    protected string $carrierName = '';

    /** @var \DateTimeInterface|null Creation date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    /** @var string Optional Identcode */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('identCode')]
    #[Serializer\Accessor(getter: 'getIdentCode', setter: 'setIdentCode')]
    protected string $identCode = '';

    /** @var string Optional shipment note */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('trackingUrl')]
    #[Serializer\Accessor(getter: 'getTrackingUrl', setter: 'setTrackingUrl')]
    protected string $trackingUrl = '';

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->deliveryNoteId = new Identity();
    }

    /**
     * @return Identity Reference to deliveryNote
     * @throws MustNotBeNullException|\TypeError
     */
    public function getDeliveryNoteId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->deliveryNoteId);
    }

    /**
     * @param Identity $deliveryNoteId Reference to deliveryNote
     *
     * @return $this
     */
    public function setDeliveryNoteId(Identity $deliveryNoteId): self
    {
        $this->deliveryNoteId = $deliveryNoteId;

        return $this;
    }

    /**
     * @return string Carrier name
     */
    public function getCarrierName(): string
    {
        return $this->carrierName;
    }

    /**
     * @param string $carrierName Carrier name
     *
     * @return $this
     */
    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null Creation date
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface|null $creationDate Creation date
     *
     * @return $this
     */
    public function setCreationDate(?\DateTimeInterface $creationDate = null): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return string Optional Identcode
     */
    public function getIdentCode(): string
    {
        return $this->identCode;
    }

    /**
     * @param string $identCode Optional Identcode
     *
     * @return $this
     */
    public function setIdentCode(string $identCode): self
    {
        $this->identCode = $identCode;

        return $this;
    }

    /**
     * @return string Optional shipment note
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note Optional shipment note
     *
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingUrl(): string
    {
        return $this->trackingUrl;
    }

    /**
     * @param string $trackingUrl
     *
     * @return $this
     */
    public function setTrackingUrl(string $trackingUrl): self
    {
        $this->trackingUrl = $trackingUrl;

        return $this;
    }
}

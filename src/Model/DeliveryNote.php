<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * A delivery note created for shipment.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class DeliveryNote extends AbstractIdentity implements ItemsInterface
{
    /** @var Identity Reference to customerOrder */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerOrderId')]
    #[Serializer\Accessor(getter: 'getCustomerOrderId', setter: 'setCustomerOrderId')]
    protected Identity $customerOrderId;

    /** @var \DateTimeInterface|null Creation date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    /** @var bool Optional flag for fulfillment. True, if delivery ist fulfilled by someone else */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isFulfillment')]
    #[Serializer\Accessor(getter: 'getIsFulfillment', setter: 'setIsFulfillment')]
    protected bool $isFulfillment = false;

    /** @var string Optional text note */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    /** @var DeliveryNoteItem[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\DeliveryNoteItem>')]
    #[Serializer\SerializedName('items')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $items = [];

    /** @var DeliveryNoteTrackingList[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\DeliveryNoteTrackingList>')]
    #[Serializer\SerializedName('trackingLists')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $trackingLists = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->customerOrderId = new Identity();
    }

    /**
     * @return Identity Reference to customerOrder
     */
    public function getCustomerOrderId(): Identity
    {
        return $this->customerOrderId;
    }

    /**
     * @param Identity $customerOrderId Reference to customerOrder
     *
     * @return $this
     */
    public function setCustomerOrderId(Identity $customerOrderId): self
    {
        $this->customerOrderId = $customerOrderId;

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
     * @return bool Optional flag for fulfillment. True, if delivery ist fulfilled by someone else
     */
    public function getIsFulfillment(): bool
    {
        return $this->isFulfillment;
    }

    /**
     * @param bool $isFulfillment Optional flag for fulfillment. True, if delivery ist fulfilled by someone else
     *
     * @return $this
     */
    public function setIsFulfillment(bool $isFulfillment): self
    {
        $this->isFulfillment = $isFulfillment;

        return $this;
    }

    /**
     * @return string Optional text note
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note Optional text note
     *
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @phpstan-param DeliveryNoteItem $item
     *
     * @param AbstractModel $item
     *
     * @return $this
     */
    public function addItem(AbstractModel $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return DeliveryNoteItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @phpstan-param DeliveryNoteItem ...$items
     *
     * @param AbstractModel ...$items
     *
     * @return $this
     */
    public function setItems(AbstractModel ...$items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clearItems(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * @param DeliveryNoteTrackingList $trackingList
     *
     * @return $this
     */
    public function addTrackingList(DeliveryNoteTrackingList $trackingList): self
    {
        $this->trackingLists[] = $trackingList;

        return $this;
    }

    /**
     * @return DeliveryNoteTrackingList[]
     */
    public function getTrackingLists(): array
    {
        return $this->trackingLists;
    }

    /**
     * @param DeliveryNoteTrackingList ...$trackingLists
     *
     * @return $this
     */
    public function setTrackingLists(DeliveryNoteTrackingList ...$trackingLists): self
    {
        $this->trackingLists = $trackingLists;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearTrackingLists(): self
    {
        $this->trackingLists = [];

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIdentificationStrings(): array
    {
        $this->setIdentificationStringBySubject(
            'customerOrderId',
            \sprintf('Related type CustomerOrder (JTL-Wawi PK = %d)', $this->customerOrderId->getHost())
        );

        return parent::getIdentificationStrings();
    }
}

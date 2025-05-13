<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Special product price properties to specify date and stock limits.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductSpecialPrice extends AbstractIdentity implements ItemsInterface
{
    /** @var \DateTimeInterface|null Optional: Activate special price from date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('activeFromDate')]
    #[Serializer\Accessor(getter: 'getActiveFromDate', setter: 'setActiveFromDate')]
    protected ?\DateTimeInterface $activeFromDate = null;

    /** @var \DateTimeInterface|null Optional: Special price active until date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('activeUntilDate')]
    #[Serializer\Accessor(getter: 'getActiveUntilDate', setter: 'setActiveUntilDate')]
    protected ?\DateTimeInterface $activeUntilDate = null;

    /**
     * @var bool Optional: Consider activeFrom/activeUntil date range.
     *              If true, specialPrice will get active from activeFrom-date and will stop after activeUntil-date.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('considerDateLimit')]
    #[Serializer\Accessor(getter: 'getConsiderDateLimit', setter: 'setConsiderDateLimit')]
    protected bool $considerDateLimit = false;

    /**
     * @var bool Optional: Consider stockLimit value.
     *              If true, specialPrice will be only active until product stockLevel is greater or equal stockLimit.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('considerStockLimit')]
    #[Serializer\Accessor(getter: 'getConsiderStockLimit', setter: 'setConsiderStockLimit')]
    protected bool $considerStockLimit = false;

    /**
     * @var bool Special price is active? Default true, to activate specialPrice.
     *              Special price can still be inactivated, if date or stock Limitations do not match.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isActive')]
    #[Serializer\Accessor(getter: 'getIsActive', setter: 'setIsActive')]
    protected bool $isActive = false;

    /** @var int Optional: SpecialPrice active until stock level quantity */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('stockLimit')]
    #[Serializer\Accessor(getter: 'getStockLimit', setter: 'setStockLimit')]
    protected int $stockLimit = 0;

    /** @var ProductSpecialPriceItem[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductSpecialPriceItem>')]
    #[Serializer\SerializedName('items')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $items = [];

    /**
     * @return \DateTimeInterface|null Optional: Activate special price from date
     */
    public function getActiveFromDate(): ?\DateTimeInterface
    {
        return $this->activeFromDate;
    }

    /**
     * @param \DateTimeInterface|null $activeFromDate Optional: Activate special price from date
     *
     * @return $this
     */
    public function setActiveFromDate(?\DateTimeInterface $activeFromDate = null): self
    {
        $this->activeFromDate = $activeFromDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null Optional: Special price active until date
     */
    public function getActiveUntilDate(): ?\DateTimeInterface
    {
        return $this->activeUntilDate;
    }

    /**
     * @param \DateTimeInterface|null $activeUntilDate Optional: Special price active until date
     *
     * @return $this
     */
    public function setActiveUntilDate(?\DateTimeInterface $activeUntilDate = null): self
    {
        $this->activeUntilDate = $activeUntilDate;

        return $this;
    }

    /**
     * @return bool Optional: Consider activeFrom/activeUntil date range.
     *                  If true, specialPrice will get active from activeFrom-date and will stop after activeUntil-date.
     */
    public function getConsiderDateLimit(): bool
    {
        return $this->considerDateLimit;
    }

    /**
     * @param bool $considerDateLimit Optional: Consider activeFrom/activeUntil date range.
     *                                If true, specialPrice will get active from activeFrom-date and will
     *                                stop after activeUntil-date.
     *
     * @return $this
     */
    public function setConsiderDateLimit(bool $considerDateLimit): self
    {
        $this->considerDateLimit = $considerDateLimit;

        return $this;
    }

    /**
     * @return bool Optional: Consider stockLimit value.
     *                  If true, specialPrice will be only active until product
     *                  stockLevel is greater or equal stockLimit.
     */
    public function getConsiderStockLimit(): bool
    {
        return $this->considerStockLimit;
    }

    /**
     * @param bool $considerStockLimit Optional: Consider stockLimit value.
     *                                 If true, specialPrice will be only active until product stockLevel
     *                                 is greater or equal stockLimit.
     *
     * @return $this
     */
    public function setConsiderStockLimit(bool $considerStockLimit): self
    {
        $this->considerStockLimit = $considerStockLimit;

        return $this;
    }

    /**
     * @return bool Special price is active? Default true, to activate specialPrice.
     *                  Special price can still be inactivated, if date or stock Limitations do not match.
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive Special price is active? Default true, to activate specialPrice.
     *                       Special price can still be inactivated, if date or stock Limitations do not
     *                       match.
     *
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return int Optional: SpecialPrice active until stock level quantity
     */
    public function getStockLimit(): int
    {
        return $this->stockLimit;
    }

    /**
     * @param int $stockLimit Optional: SpecialPrice active until stock level quantity
     *
     * @return $this
     */
    public function setStockLimit(int $stockLimit): self
    {
        $this->stockLimit = $stockLimit;

        return $this;
    }

    /**
     * @phpstan-param ProductSpecialPriceItem $item
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
     * @return ProductSpecialPriceItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @phpstan-param ProductSpecialPriceItem ...$items
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
}

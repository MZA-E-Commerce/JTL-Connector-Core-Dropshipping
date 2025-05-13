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
class DeliveryNoteItemInfo extends AbstractModel
{
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('batch')]
    #[Serializer\Accessor(getter: 'getBatch', setter: 'setBatch')]
    protected string $batch = '';

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('bestBefore')]
    #[Serializer\Accessor(getter: 'getBestBefore', setter: 'setBestBefore')]
    protected ?\DateTimeInterface $bestBefore = null;


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('quantity')]
    #[Serializer\Accessor(getter: 'getQuantity', setter: 'setQuantity')]
    protected float $quantity = 0.0;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('warehouseId')]
    #[Serializer\Accessor(getter: 'getWarehouseId', setter: 'setWarehouseId')]
    protected int $warehouseId = 0;

    /**
     * @return string
     */
    public function getBatch(): string
    {
        return $this->batch;
    }

    /**
     * @param string $batch
     *
     * @return $this
     */
    public function setBatch(string $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBestBefore(): ?\DateTimeInterface
    {
        return $this->bestBefore;
    }

    /**
     * @param \DateTimeInterface|null $bestBefore
     *
     * @return $this
     */
    public function setBestBefore(?\DateTimeInterface $bestBefore = null): self
    {
        $this->bestBefore = $bestBefore;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     *
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    /**
     * @param int $warehouseId
     *
     * @return $this
     */
    public function setWarehouseId(int $warehouseId): self
    {
        $this->warehouseId = $warehouseId;

        return $this;
    }
}

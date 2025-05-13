<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;

/**
 * Product to warehouse info association.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductWarehouseInfo extends AbstractModel
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('warehouseId')]
    #[Serializer\Accessor(getter: 'getWarehouseId', setter: 'setWarehouseId')]
    protected Identity $warehouseId;

    /** @var double Optional product inflow quantity for specified warehouse */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('inflowQuantity')]
    #[Serializer\Accessor(getter: 'getInflowQuantity', setter: 'setInflowQuantity')]
    protected float $inflowQuantity = 0.0;


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('stockLevel')]
    #[Serializer\Accessor(getter: 'getStockLevel', setter: 'setStockLevel')]
    protected float $stockLevel = 0.0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->warehouseId = new Identity();
    }

    /**
     * @return Identity
     * @throws MustNotBeNullException|\TypeError
     */
    public function getWarehouseId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->warehouseId);
    }

    /**
     * @param Identity $warehouseId
     *
     * @return $this
     */
    public function setWarehouseId(Identity $warehouseId): self
    {
        $this->warehouseId = $warehouseId;

        return $this;
    }

    /**
     * @return float Optional product inflow quantity for specified warehouse
     */
    public function getInflowQuantity(): float
    {
        return $this->inflowQuantity;
    }

    /**
     * @param float $inflowQuantity Optional product inflow quantity for specified warehouse
     *
     * @return $this
     */
    public function setInflowQuantity(float $inflowQuantity): self
    {
        $this->inflowQuantity = $inflowQuantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getStockLevel(): float
    {
        return $this->stockLevel;
    }

    /**
     * @param float $stockLevel
     *
     * @return $this
     */
    public function setStockLevel(float $stockLevel): self
    {
        $this->stockLevel = $stockLevel;

        return $this;
    }
}

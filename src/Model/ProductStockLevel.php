<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductStockLevel extends AbstractModel
{
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('stockLevel')]
    #[Serializer\Accessor(getter: 'getStockLevel', setter: 'setStockLevel')]
    protected float $stockLevel = 0.0;

    /**
     * ProductStockLevel constructor.
     */
    public function __construct()
    {
        $this->productId = new Identity();
    }

    /**
     * @return Identity
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getProductId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->productId);
    }

    /**
     * @param Identity $productId
     *
     * @return $this
     */
    public function setProductId(Identity $productId): self
    {
        $this->productId = $productId;

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

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Define set articles / parts lists.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class PartsList extends AbstractIdentity
{
    /** @var Identity Reference to a component / product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;

    /** @var double Component quantity */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('quantity')]
    #[Serializer\Accessor(getter: 'getQuantity', setter: 'setQuantity')]
    protected float $quantity = 0.0;

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->productId = new Identity();
    }

    /**
     * @return Identity Reference to a component / product
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getProductId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->productId);
    }

    /**
     * @param Identity $productId Reference to a component / product
     *
     * @return $this
     */
    public function setProductId(Identity $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return float Component quantity
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity Component quantity
     *
     * @return PartsList
     */
    public function setQuantity(float $quantity): PartsList
    {
        $this->quantity = $quantity;

        return $this;
    }
}

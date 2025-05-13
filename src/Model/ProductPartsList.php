<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Define set articles / parts lists.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductPartsList extends AbstractIdentity
{
    /** @var double Component quantity */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('quantity')]
    #[Serializer\Accessor(getter: 'getQuantity', setter: 'setQuantity')]
    protected float $quantity = 0.0;

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
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Shipping Address properties of a customer (order)
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CustomerOrderShippingAddress extends AbstractOrderAddress
{
    /** @var Identity Reference to customer */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerId')]
    #[Serializer\Accessor(getter: 'getCustomerId', setter: 'setCustomerId')]
    protected Identity $customerId;

    /**
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->customerId = new Identity();
    }

    /**
     * @return Identity Reference to customer
     */
    public function getCustomerId(): Identity
    {
        return $this->customerId;
    }

    /**
     * @param Identity $customerId Reference to customer
     *
     * @return $this
     */
    public function setCustomerId(Identity $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }
}

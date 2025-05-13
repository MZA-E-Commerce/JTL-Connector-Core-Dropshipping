<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Product to specificValue Assignment.
 * Product specifics are used to assign characteristic product attributes like color or  size...
 * When different products have common specifics, products are similar.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductSpecific extends AbstractIdentity
{
    /** @var Identity Reference to specificValue */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('specificValueId')]
    #[Serializer\Accessor(getter: 'getSpecificValueId', setter: 'setSpecificValueId')]
    protected Identity $specificValueId ;

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->specificValueId = new Identity();
    }

    /**
     * @return Identity Reference to specificValue
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getSpecificValueId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->specificValueId);
    }

    /**
     * @param Identity $specificValueId Reference to specificValue
     *
     * @return $this
     */
    public function setSpecificValueId(Identity $specificValueId): self
    {
        $this->specificValueId = $specificValueId;

        return $this;
    }
}

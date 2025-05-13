<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Product-Category Allocation.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Product2Category extends AbstractIdentity
{
    /** @var Identity Reference to category */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('categoryId')]
    #[Serializer\Accessor(getter: 'getCategoryId', setter: 'setCategoryId')]
    protected Identity $categoryId;

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->categoryId = new Identity();
    }

    /**
     * @return Identity Reference to category
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getCategoryId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->categoryId);
    }

    /**
     * @param Identity $categoryId Reference to category
     *
     * @return $this
     */
    public function setCategoryId(Identity $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }
}

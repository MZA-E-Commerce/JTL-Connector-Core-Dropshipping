<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Product-ConfigGroup Assignment.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ProductConfigGroup extends AbstractModel
{
    /** @var Identity Reference to configGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('configGroupId')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected Identity $configGroupId;

    /** @var int Optional sort number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configGroupId = new Identity();
    }

    /**
     * @return Identity Reference to configGroup
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getConfigGroupId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->configGroupId);
    }

    /**
     * @param Identity $configGroupId Reference to configGroup
     *
     * @return $this
     */
    public function setConfigGroupId(Identity $configGroupId): self
    {
        $this->configGroupId = $configGroupId;

        return $this;
    }

    /**
     * @return int Optional sort number
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort number
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}

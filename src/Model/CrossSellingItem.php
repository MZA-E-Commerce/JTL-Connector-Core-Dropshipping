<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Specify cross-sold products that are in a common crossSellingGroup.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CrossSellingItem extends AbstractIdentity
{
    /** @var Identity Reference to crossSellingGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('crossSellingGroupId')]
    #[Serializer\Accessor(getter: 'getCrossSellingGroupId', setter: 'setCrossSellingGroupId')]
    protected Identity $crossSellingGroupId;

    #[Serializer\Type(CrossSellingGroup::class)]
    #[Serializer\SerializedName('crossSellingGroup')]
    #[Serializer\Accessor(getter: 'getCrossSellingGroup', setter: 'setCrossSellingGroup')]
    protected ?CrossSellingGroup $crossSellingGroup = null;

    /** @var Identity[] Referenced target product ID */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Identity>')]
    #[Serializer\SerializedName('productIds')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $productIds = [];

    /**
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->crossSellingGroupId = new Identity();
    }

    /**
     * @return Identity Reference to crossSellingGroup
     */
    public function getCrossSellingGroupId(): Identity
    {
        return $this->crossSellingGroupId;
    }

    /**
     * @param Identity $crossSellingGroupId Reference to crossSellingGroup
     *
     * @return $this
     */
    public function setCrossSellingGroupId(Identity $crossSellingGroupId): self
    {
        $this->crossSellingGroupId = $crossSellingGroupId;

        return $this;
    }

    /**
     * @return CrossSellingGroup|null
     */
    public function getCrossSellingGroup(): ?CrossSellingGroup
    {
        return $this->crossSellingGroup;
    }

    /**
     * @param CrossSellingGroup|null $crossSellingGroup
     *
     * @return $this
     */
    public function setCrossSellingGroup(?CrossSellingGroup $crossSellingGroup): self
    {
        $this->crossSellingGroup = $crossSellingGroup;
        return $this;
    }

    /**
     * @param Identity $productId
     *
     * @return $this
     */
    public function addProductId(Identity $productId): self
    {
        $this->productIds[] = $productId;

        return $this;
    }

    /**
     * @return Identity[]
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }

    /**
     * @param Identity ...$productIds
     *
     * @return $this
     */
    public function setProductIds(Identity ...$productIds): self
    {
        $this->productIds = $productIds;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearProductIds(): self
    {
        $this->productIds = [];

        return $this;
    }
}

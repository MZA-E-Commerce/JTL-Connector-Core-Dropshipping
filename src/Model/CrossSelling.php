<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Group cross-sold products belonging to different crossSellingGroups together.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class CrossSelling extends AbstractIdentity implements ItemsInterface
{
    /** @var Identity Source product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;

    /** @var CrossSellingItem[] Referenced cross-sold products grouped by their crossSellingGroup */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CrossSellingItem>')]
    #[Serializer\SerializedName('items')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $items = [];

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
     * @return Identity Source product
     */
    public function getProductId(): Identity
    {
        return $this->productId;
    }

    /**
     * @param Identity $productId Source product
     *
     * @return $this
     */
    public function setProductId(Identity $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @phpstan-param CrossSellingItem $item
     *
     * @param AbstractModel $item
     *
     * @return $this
     */
    public function addItem(AbstractModel $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return CrossSellingItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @phpstan-param CrossSellingItem ...$items
     *
     * @param AbstractModel ...$items
     *
     * @return $this
     */
    public function setItems(AbstractModel ...$items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clearItems(): self
    {
        $this->items = [];

        return $this;
    }
}

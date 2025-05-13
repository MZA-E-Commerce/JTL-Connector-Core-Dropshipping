<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * A config Item that is displayed in a config Group.
 * Config item can reference to a specific productId to inherit price, name and description.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class ConfigItem extends AbstractIdentity
{
    /** @var Identity Reference to configGroup */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('configGroupId')]
    #[Serializer\Accessor(getter: 'getConfigGroupId', setter: 'setConfigGroupId')]
    protected Identity $configGroupId;

    /** @var Identity Optional reference to product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productId')]
    #[Serializer\Accessor(getter: 'getProductId', setter: 'setProductId')]
    protected Identity $productId;

    /**
     * @var int     Optional:Ignore multiplier.
     *                  If true, quantity of config item will not be increased if product quantity is increased
     */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('ignoreMultiplier')]
    #[Serializer\Accessor(getter: 'getIgnoreMultiplier', setter: 'setIgnoreMultiplier')]
    protected int $ignoreMultiplier = 0;

    /**
     * @var bool Optional: Inherit product name and description  if productId is set.
     *                  If true, configItem name will be received from referenced
     *                  product and configItemI18n name will be ignored.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('inheritProductName')]
    #[Serializer\Accessor(getter: 'getInheritProductName', setter: 'setInheritProductName')]
    protected bool $inheritProductName = false;

    /**
     * @var bool Optional: Inherit product price of referenced productId.
     *              If true, configItem price will be the same as referenced product price.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('inheritProductPrice')]
    #[Serializer\Accessor(getter: 'getInheritProductPrice', setter: 'setInheritProductPrice')]
    protected bool $inheritProductPrice = false;

    /** @var double Optional initial / predefined quantity. Default is one (1) quantity piece. */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('initialQuantity')]
    #[Serializer\Accessor(getter: 'getInitialQuantity', setter: 'setInitialQuantity')]
    protected float $initialQuantity = 0.0;

    /** @var bool Optional: Preselect configItem. If true, configItem will be preselected or prechecked. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isPreSelected')]
    #[Serializer\Accessor(getter: 'getIsPreSelected', setter: 'setIsPreSelected')]
    protected bool $isPreSelected = false;

    /** @var bool Optional: Highlight or recommend config item. If true, configItem will be recommended/highlighted. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isRecommended')]
    #[Serializer\Accessor(getter: 'getIsRecommended', setter: 'setIsRecommended')]
    protected bool $isRecommended = false;

    /** @var double Maximum allowed quantity. Default 0 for no maximum limit. */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('maxQuantity')]
    #[Serializer\Accessor(getter: 'getMaxQuantity', setter: 'setMaxQuantity')]
    protected float $maxQuantity = 0.0;

    /** @var double Optional minimum quantity required to add configItem. Default 0 for no minimum quantity. */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('minQuantity')]
    #[Serializer\Accessor(getter: 'getMinQuantity', setter: 'setMinQuantity')]
    protected float $minQuantity = 0.0;

    /**
     * @var bool Optional: Show discount compared to productId price.
     *              If true, the discount compared to referenct product price will be shown.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('showDiscount')]
    #[Serializer\Accessor(getter: 'getShowDiscount', setter: 'setShowDiscount')]
    protected bool $showDiscount = false;

    /** @var bool Optional: Show surcharge compared to productId price. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('showSurcharge')]
    #[Serializer\Accessor(getter: 'getShowSurcharge', setter: 'setShowSurcharge')]
    protected bool $showSurcharge = false;

    /** @var int Optional sort order number */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    /** @var int Config item type. 0: Product, 1: Special */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('type')]
    #[Serializer\Accessor(getter: 'getType', setter: 'setType')]
    protected int $type = 0;

    /** @var ConfigItemI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ConfigItemI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /** @var ConfigItemPrice[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ConfigItemPrice>')]
    #[Serializer\SerializedName('prices')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $prices = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->configGroupId = new Identity();
        $this->productId     = new Identity();
    }

    /**
     * @return Identity Reference to configGroup
     */
    public function getConfigGroupId(): Identity
    {
        return $this->configGroupId;
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
     * @return Identity Optional reference to product
     */
    public function getProductId(): Identity
    {
        return $this->productId;
    }

    /**
     * @param Identity $productId Optional reference to product
     *
     * @return $this
     */
    public function setProductId(Identity $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int Optional:Ignore multiplier.
     *                  If true, quantity of config item will not be increased if product quantity is increased
     */
    public function getIgnoreMultiplier(): int
    {
        return $this->ignoreMultiplier;
    }

    /**
     * @param int $ignoreMultiplier Optional:Ignore multiplier.
     *                                  If true, quantity of config item will not be increased
     *                                  if product quantity is increased
     *
     * @return $this
     */
    public function setIgnoreMultiplier(int $ignoreMultiplier): self
    {
        $this->ignoreMultiplier = $ignoreMultiplier;

        return $this;
    }

    /**
     * @return bool Optional: Inherit product name and description  if productId is set.
     *                  If true, configItem name will be received from referenced product
     *                  and configItemI18n name will be ignored.
     */
    public function getInheritProductName(): bool
    {
        return $this->inheritProductName;
    }

    /**
     * @param bool $inheritProductName Optional: Inherit product name and description  if productId is set.
     *                                    If true, configItem name will be received from referenced product and
     *                                    configItemI18n name will be ignored.
     *
     * @return $this
     */
    public function setInheritProductName(bool $inheritProductName): self
    {
        $this->inheritProductName = $inheritProductName;

        return $this;
    }

    /**
     * @return bool Optional: Inherit product price of referenced productId.
     *                  If true, configItem price will be the same as referenced product price.
     */
    public function getInheritProductPrice(): bool
    {
        return $this->inheritProductPrice;
    }

    /**
     * @param bool $inheritProductPrice Optional: Inherit product price of referenced productId.
     *                                     If true, configItem price will be the same as referenced product price.
     *
     * @return $this
     */
    public function setInheritProductPrice(bool $inheritProductPrice): self
    {
        $this->inheritProductPrice = $inheritProductPrice;

        return $this;
    }

    /**
     * @return float Optional initial / predefined quantity. Default is one (1) quantity piece.
     */
    public function getInitialQuantity(): float
    {
        return $this->initialQuantity;
    }

    /**
     * @param float $initialQuantity Optional initial / predefined quantity. Default is one (1) quantity piece.
     *
     * @return $this
     */
    public function setInitialQuantity(float $initialQuantity): self
    {
        $this->initialQuantity = $initialQuantity;

        return $this;
    }

    /**
     * @return bool Optional: Preselect configItem. If true, configItem will be preselected or prechecked.
     */
    public function getIsPreSelected(): bool
    {
        return $this->isPreSelected;
    }

    /**
     * @param bool $isPreSelected Optional: Preselect configItem.
     *                               If true, configItem will be preselected or prechecked.
     *
     * @return $this
     */
    public function setIsPreSelected(bool $isPreSelected): self
    {
        $this->isPreSelected = $isPreSelected;

        return $this;
    }

    /**
     * @return bool Optional: Highlight or recommend config item.
     *                  If true, configItem will be recommended/highlighted.
     */
    public function getIsRecommended(): bool
    {
        return $this->isRecommended;
    }

    /**
     * @param bool $isRecommended Optional: Highlight or recommend config item.
     *                               If true, configItem will be recommended/highlighted.
     *
     * @return $this
     */
    public function setIsRecommended(bool $isRecommended): self
    {
        $this->isRecommended = $isRecommended;

        return $this;
    }

    /**
     * @return float Maximum allowed quantity. Default 0 for no maximum limit.
     */
    public function getMaxQuantity(): float
    {
        return $this->maxQuantity;
    }

    /**
     * @param float $maxQuantity Maximum allowed quantity. Default 0 for no maximum limit.
     *
     * @return $this
     */
    public function setMaxQuantity(float $maxQuantity): self
    {
        $this->maxQuantity = $maxQuantity;

        return $this;
    }

    /**
     * @return float Optional minimum quantity required to add configItem. Default 0 for no minimum quantity.
     */
    public function getMinQuantity(): float
    {
        return $this->minQuantity;
    }

    /**
     * @param float $minQuantity Optional minimum quantity required to add configItem.
     *                           Default 0 for no minimum quantity.
     *
     * @return $this
     */
    public function setMinQuantity(float $minQuantity): self
    {
        $this->minQuantity = $minQuantity;

        return $this;
    }

    /**
     * @return bool Optional: Show discount compared to productId price.
     *                  If true, the discount compared to referenct product price will be shown.
     */
    public function getShowDiscount(): bool
    {
        return $this->showDiscount;
    }

    /**
     * @param bool $showDiscount Optional: Show discount compared to productId price.
     *                              If true, the discount compared to referenct product price will be shown.
     *
     * @return $this
     */
    public function setShowDiscount(bool $showDiscount): self
    {
        $this->showDiscount = $showDiscount;

        return $this;
    }

    /**
     * @return bool Optional: Show surcharge compared to productId price.
     */
    public function getShowSurcharge(): bool
    {
        return $this->showSurcharge;
    }

    /**
     * @param bool $showSurcharge Optional: Show surcharge compared to productId price.
     *
     * @return $this
     */
    public function setShowSurcharge(bool $showSurcharge): self
    {
        $this->showSurcharge = $showSurcharge;

        return $this;
    }

    /**
     * @return int Optional sort order number
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort order number
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return int Config item type. 0: Product, 1: Special
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type Config item type. 0: Product, 1: Special
     *
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param ConfigItemI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ConfigItemI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

        return $this;
    }

    /**
     * @return ConfigItemI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ConfigItemI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ConfigItemI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearI18ns(): self
    {
        $this->i18ns = [];

        return $this;
    }

    /**
     * @param ConfigItemPrice $price
     *
     * @return $this
     */
    public function addPrice(ConfigItemPrice $price): self
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * @return ConfigItemPrice[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param ConfigItemPrice ...$prices
     *
     * @return $this
     */
    public function setPrices(ConfigItemPrice ...$prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearPrices(): self
    {
        $this->prices = [];

        return $this;
    }
}

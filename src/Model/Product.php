<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use DateTimeInterface;
use JMS\Serializer\Annotation as Serializer;
use Jtl\Connector\Core\Exception\MustNotBeNullException;
use Jtl\Connector\Core\Utilities\Validator\Validate;
use TypeError;

/**
 * Product properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Product extends AbstractIdentity implements TranslatableAttributesInterface
{
    use TranslatableAttributesTrait;

    /** @var Identity Optional reference to basePriceUnit */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('basePriceUnitId')]
    #[Serializer\Accessor(getter: 'getBasePriceUnitId', setter: 'setBasePriceUnitId')]
    protected Identity $basePriceUnitId;

    /** @var Identity Reference to manufacturer */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('manufacturerId')]
    #[Serializer\Accessor(getter: 'getManufacturerId', setter: 'setManufacturerId')]
    protected Identity $manufacturerId;

    /** @var Identity Reference to master product */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('masterProductId')]
    #[Serializer\Accessor(getter: 'getMasterProductId', setter: 'setMasterProductId')]
    protected Identity $masterProductId;

    /** @var Identity Optional reference to measurement unit id */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('measurementUnitId')]
    #[Serializer\Accessor(getter: 'getMeasurementUnitId', setter: 'setMeasurementUnitId')]
    protected Identity $measurementUnitId;

    /** @var Identity Optional reference to partsList */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('partsListId')]
    #[Serializer\Accessor(getter: 'getPartsListId', setter: 'setPartsListId')]
    protected Identity $partsListId;

    /** @var Identity Optional reference to productType */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('productTypeId')]
    #[Serializer\Accessor(getter: 'getProductTypeId', setter: 'setProductTypeId')]
    protected Identity $productTypeId;

    /** @var Identity Reference to shippingClass */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('shippingClassId')]
    #[Serializer\Accessor(getter: 'getShippingClassId', setter: 'setShippingClassId')]
    protected Identity $shippingClassId;

    /** @var Identity Reference to tax class */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('taxClassId')]
    #[Serializer\Accessor(getter: 'getTaxClassId', setter: 'setTaxClassId')]
    protected Identity $taxClassId;

    /** @var Identity Reference to unit */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('unitId')]
    #[Serializer\Accessor(getter: 'getUnitId', setter: 'setUnitId')]
    protected Identity $unitId;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('additionalHandlingTime')]
    #[Serializer\Accessor(getter: 'getAdditionalHandlingTime', setter: 'setAdditionalHandlingTime')]
    protected int $additionalHandlingTime = 0;

    /** @var string Optional Amazon Standard Identification Number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('asin')]
    #[Serializer\Accessor(getter: 'getAsin', setter: 'setAsin')]
    protected string $asin = '';

    /** @var \DateTimeInterface|null Optional available from date. Specify a date, upon when product can be purchased. */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('availableFrom')]
    #[Serializer\Accessor(getter: 'getAvailableFrom', setter: 'setAvailableFrom')]
    protected ?\DateTimeInterface $availableFrom = null;

    /**
     * @var double Optional base price divisor.
     * Calculate basePriceDivisor by dividing product filling quantity through unit pricing base measure.
     * E.g. 75ml / 100ml = 0.75
     */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('basePriceDivisor')]
    #[Serializer\Accessor(getter: 'getBasePriceDivisor', setter: 'setBasePriceDivisor')]
    protected float $basePriceDivisor = 0.0;


    #[Serializer\Type('double')]
    #[Serializer\SerializedName('basePriceFactor')]
    #[Serializer\Accessor(getter: 'getBasePriceFactor', setter: 'setBasePriceFactor')]
    protected float $basePriceFactor = 0.0;

    /** @var double Optional base price quantity */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('basePriceQuantity')]
    #[Serializer\Accessor(getter: 'getBasePriceQuantity', setter: 'setBasePriceQuantity')]
    protected float $basePriceQuantity = 0.0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('basePriceUnitCode')]
    #[Serializer\Accessor(getter: 'getBasePriceUnitCode', setter: 'setBasePriceUnitCode')]
    protected string $basePriceUnitCode = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('basePriceUnitName')]
    #[Serializer\Accessor(getter: 'getBasePriceUnitName', setter: 'setBasePriceUnitName')]
    protected string $basePriceUnitName = '';

    /** @var bool Optional: Set to true to display base price / unit pricing measure */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('considerBasePrice')]
    #[Serializer\Accessor(getter: 'getConsiderBasePrice', setter: 'setConsiderBasePrice')]
    protected bool $considerBasePrice = false;

    /**
     * @var bool Consider stock level?
     * If true, product can only be purchased with a positive stockLevel or when permitNegativeStock is set to true
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('considerStock')]
    #[Serializer\Accessor(getter: 'getConsiderStock', setter: 'setConsiderStock')]
    protected bool $considerStock = false;

    /** @var bool Optional: Consider stock levels of productVariations. Same as considerStock but for variations. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('considerVariationStock')]
    #[Serializer\Accessor(getter: 'getConsiderVariationStock', setter: 'setConsiderVariationStock')]
    protected bool $considerVariationStock = false;

    /** @var \DateTimeInterface|null Creation date */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('discountable')]
    #[Serializer\Accessor(getter: 'getDiscountable', setter: 'setDiscountable')]
    protected bool $discountable = true;

    /** @var string Optional European Article Number (EAN) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('ean')]
    #[Serializer\Accessor(getter: 'getEan', setter: 'setEan')]
    protected string $ean = '';

    /** @var string Optional Ebay product ID */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('epid')]
    #[Serializer\Accessor(getter: 'getEpid', setter: 'setEpid')]
    protected string $epid = '';

    /** @var string Optional Hazard identifier, encodes general hazard class und subdivision */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('hazardIdNumber')]
    #[Serializer\Accessor(getter: 'getHazardIdNumber', setter: 'setHazardIdNumber')]
    protected string $hazardIdNumber = '';

    /** @var double Optional product height */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('height')]
    #[Serializer\Accessor(getter: 'getHeight', setter: 'setHeight')]
    protected float $height = 0.0;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isActive')]
    #[Serializer\Accessor(getter: 'getIsActive', setter: 'setIsActive')]
    protected bool $isActive = false;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isBatch')]
    #[Serializer\Accessor(getter: 'getIsBatch', setter: 'setIsBatch')]
    protected bool $isBatch = false;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isBestBefore')]
    #[Serializer\Accessor(getter: 'getIsBestBefore', setter: 'setIsBestBefore')]
    protected bool $isBestBefore = false;

    /** @var string Optional International Standard Book Number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('isbn')]
    #[Serializer\Accessor(getter: 'getIsbn', setter: 'setIsbn')]
    protected string $isbn = '';

    /** @var bool Optional: Set to true to allow non-integer quantites for purchase */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isDivisible')]
    #[Serializer\Accessor(getter: 'getIsDivisible', setter: 'setIsDivisible')]
    protected bool $isDivisible = false;

    /** @var bool Optional flag if product is master product */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isMasterProduct')]
    #[Serializer\Accessor(getter: 'getIsMasterProduct', setter: 'setIsMasterProduct')]
    protected bool $isMasterProduct = false;

    /**
     * @var bool Optional flag new product.
     * If true, product will be highlighted as new (creation date may also be considered)
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isNewProduct')]
    #[Serializer\Accessor(getter: 'getIsNewProduct', setter: 'setIsNewProduct')]
    protected bool $isNewProduct = false;

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isSerialNumber')]
    #[Serializer\Accessor(getter: 'getIsSerialNumber', setter: 'setIsSerialNumber')]
    protected bool $isSerialNumber = false;

    /**
     * @var bool Optional flag top product.
     * If true, product will be highlighted as top product (e.g. in product lists or in special boxes)
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isTopProduct')]
    #[Serializer\Accessor(getter: 'getIsTopProduct', setter: 'setIsTopProduct')]
    protected bool $isTopProduct = false;

    /** @var string Optional internal keywords and synonyms for product */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('keywords')]
    #[Serializer\Accessor(getter: 'getKeywords', setter: 'setKeywords')]
    protected string $keywords = '';

    /** @var double Optional product length */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('length')]
    #[Serializer\Accessor(getter: 'getLength', setter: 'setLength')]
    protected float $length = 0.0;

    /** @var string Optional manufacturer number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('manufacturerNumber')]
    #[Serializer\Accessor(getter: 'getManufacturerNumber', setter: 'setManufacturerNumber')]
    protected string $manufacturerNumber = '';

    #[Serializer\Type(Manufacturer::class)]
    #[Serializer\SerializedName('manufacturer')]
    #[Serializer\Accessor(getter: 'getManufacturer', setter: 'setManufacturer')]
    protected ?Manufacturer $manufacturer = null;

    /** @var double Optional measurement quantity */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('measurementQuantity')]
    #[Serializer\Accessor(getter: 'getMeasurementQuantity', setter: 'setMeasurementQuantity')]
    protected float $measurementQuantity = 0.0;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('measurementUnitCode')]
    #[Serializer\Accessor(getter: 'getMeasurementUnitCode', setter: 'setMeasurementUnitCode')]
    protected string $measurementUnitCode = '';

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('minBestBeforeDate')]
    #[Serializer\Accessor(getter: 'getMinBestBeforeDate', setter: 'setMinBestBeforeDate')]
    protected ?\DateTimeInterface $minBestBeforeDate = null;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('minimumOrderQuantity')]
    #[Serializer\Accessor(getter: 'getMinimumOrderQuantity', setter: 'setMinimumOrderQuantity')]
    protected float $minimumOrderQuantity = 0.0;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('minimumQuantity')]
    #[Serializer\Accessor(getter: 'getMinimumQuantity', setter: 'setMinimumQuantity')]
    protected float $minimumQuantity = 0.0;

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('modified')]
    #[Serializer\Accessor(getter: 'getModified', setter: 'setModified')]
    protected ?\DateTimeInterface $modified = null;

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('newReleaseDate')]
    #[Serializer\Accessor(getter: 'getNewReleaseDate', setter: 'setNewReleaseDate')]
    protected ?\DateTimeInterface $newReleaseDate = null;

    /** @var \DateTimeInterface|null Contains the date of the next available inflow. */
    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('nextAvailableInflowDate')]
    #[Serializer\Accessor(getter: 'getNextAvailableInflowDate', setter: 'setNextAvailableInflowDate')]
    protected ?\DateTimeInterface $nextAvailableInflowDate = null;

    /** @var double Contains the quantity of the next available inflow. */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('nextAvailableInflowQuantity')]
    #[Serializer\Accessor(getter: 'getNextAvailableInflowQuantity', setter: 'setNextAvailableInflowQuantity')]
    protected float $nextAvailableInflowQuantity = 0.0;

    /** @var string Optional internal product note */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    /** @var string Optional Origin country */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('originCountry')]
    #[Serializer\Accessor(getter: 'getOriginCountry', setter: 'setOriginCountry')]
    protected string $originCountry = '';

    /** @var double Optional: self can only be purchased in multiples of takeOffQuantity e.g. 5,10,15... */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('packagingQuantity')]
    #[Serializer\Accessor(getter: 'getPackagingQuantity', setter: 'setPackagingQuantity')]
    protected float $packagingQuantity = 0.0;

    /**
     * @var bool Optional Permit negative stock / allow overselling.
     * If true, product can be purchased even if stockLevel is less or equal 0 and considerStock is true.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('permitNegativeStock')]
    #[Serializer\Accessor(getter: 'getPermitNegativeStock', setter: 'setPermitNegativeStock')]
    protected bool $permitNegativeStock = false;

    /** @var double Productweight exclusive packaging */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('productWeight')]
    #[Serializer\Accessor(getter: 'getProductWeight', setter: 'setProductWeight')]
    protected float $productWeight = 0.0;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('purchasePrice')]
    #[Serializer\Accessor(getter: 'getPurchasePrice', setter: 'setPurchasePrice')]
    protected float $purchasePrice = 0.0;

    /** @var double Optional recommended retail price (gross) */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('recommendedRetailPrice')]
    #[Serializer\Accessor(getter: 'getRecommendedRetailPrice', setter: 'setRecommendedRetailPrice')]
    protected float $recommendedRetailPrice = 0.0;

    /** @var string Optional serial number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('serialNumber')]
    #[Serializer\Accessor(getter: 'getSerialNumber', setter: 'setSerialNumber')]
    protected string $serialNumber = '';

    /** @var double Productweight inclusive packaging */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('shippingWeight')]
    #[Serializer\Accessor(getter: 'getShippingWeight', setter: 'setShippingWeight')]
    protected float $shippingWeight = 0.0;

    /** @var string Optional stock keeping unit identifier */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('sku')]
    #[Serializer\Accessor(getter: 'getSku', setter: 'setSku')]
    protected string $sku = '';

    /** @var int Optional sort number for product sorting in lists */
    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('sort')]
    #[Serializer\Accessor(getter: 'getSort', setter: 'setSort')]
    protected int $sort = 0;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('stockLevel')]
    #[Serializer\Accessor(getter: 'getStockLevel', setter: 'setStockLevel')]
    protected float $stockLevel = 0.0;

    #[Serializer\Type('integer')]
    #[Serializer\SerializedName('supplierDeliveryTime')]
    #[Serializer\Accessor(getter: 'getSupplierDeliveryTime', setter: 'setSupplierDeliveryTime')]
    protected int $supplierDeliveryTime = 0;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('supplierStockLevel')]
    #[Serializer\Accessor(getter: 'getSupplierStockLevel', setter: 'setSupplierStockLevel')]
    protected float $supplierStockLevel = 0.0;

    /** @var string Optional TARIC */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('taric')]
    #[Serializer\Accessor(getter: 'getTaric', setter: 'setTaric')]
    protected string $taric = '';

    /** @var string Optional UN number, used to define hazardous properties */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('unNumber')]
    #[Serializer\Accessor(getter: 'getUnNumber', setter: 'setUnNumber')]
    protected string $unNumber = '';

    /** @var string Optional Universal Product Code */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('upc')]
    #[Serializer\Accessor(getter: 'getUpc', setter: 'setUpc')]
    protected string $upc = '';

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('vat')]
    #[Serializer\Accessor(getter: 'getVat', setter: 'setVat')]
    protected float $vat = 0.0;

    /** @var double Optional product width */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('width')]
    #[Serializer\Accessor(getter: 'getWidth', setter: 'setWidth')]
    protected float $width = 0.0;

    /** @var ProductAttribute[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductAttribute>')]
    #[Serializer\SerializedName('attributes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $attributes = [];

    /** @var Product2Category[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Product2Category>')]
    #[Serializer\SerializedName('categories')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $categories = [];

    /** @var ProductChecksum[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductChecksum>')]
    #[Serializer\SerializedName('checksums')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $checksums = [];

    /** @var ProductConfigGroup[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductConfigGroup>')]
    #[Serializer\SerializedName('configGroups')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $configGroups = [];

    /** @var CustomerGroupPackagingQuantity[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CustomerGroupPackagingQuantity>')]
    #[Serializer\SerializedName('customerGroupPackagingQuantities')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $customerGroupPackagingQuantities = [];

    /** @var ProductFileDownload[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductFileDownload>')]
    #[Serializer\SerializedName('fileDownloads')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $fileDownloads = [];

    /** @var ProductI18n[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductI18n>')]
    #[Serializer\SerializedName('i18ns')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $i18ns = [];

    /** @var ProductInvisibility[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductInvisibility>')]
    #[Serializer\SerializedName('invisibilities')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $invisibilities = [];

    /** @var ProductMediaFile[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductMediaFile>')]
    #[Serializer\SerializedName('mediaFiles')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $mediaFiles = [];

    /** @var ProductPartsList[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductPartsList>')]
    #[Serializer\SerializedName('partsLists')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $partsLists = [];

    /** @var ProductPrice[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductPrice>')]
    #[Serializer\SerializedName('prices')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $prices = [];

    /** @var ProductSpecialPrice[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductSpecialPrice>')]
    #[Serializer\SerializedName('specialPrices')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $specialPrices = [];

    /** @var ProductSpecific[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductSpecific>')]
    #[Serializer\SerializedName('specifics')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $specifics = [];

    /** @var array<TaxRate> */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\TaxRate>')]
    #[Serializer\SerializedName('taxRates')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $taxRates = [];

    /** @var ProductVariation[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductVariation>')]
    #[Serializer\SerializedName('variations')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $variations = [];

    /** @var ProductWarehouseInfo[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductWarehouseInfo>')]
    #[Serializer\SerializedName('warehouseInfo')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $warehouseInfo = [];

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param int    $host
     */
    public function __construct(string $endpoint = '', int $host = 0)
    {
        parent::__construct($endpoint, $host);
        $this->basePriceUnitId   = new Identity();
        $this->manufacturerId    = new Identity();
        $this->masterProductId   = new Identity();
        $this->measurementUnitId = new Identity();
        $this->partsListId       = new Identity();
        $this->productTypeId     = new Identity();
        $this->shippingClassId   = new Identity();
        $this->taxClassId        = new Identity();
        $this->unitId            = new Identity();
    }

    /**
     * @return bool
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function isVariation(): bool
    {
        return
            $this->isMasterProduct === true
            || Validate::checkIdentityAndNotNull($this->masterProductId)->getHost() !== 0;
    }

    /**
     * @return Identity Optional reference to basePriceUnit
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getBasePriceUnitId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->basePriceUnitId);
    }

    /**
     * @param Identity $basePriceUnitId Optional reference to basePriceUnit
     *
     * @return $this
     */
    public function setBasePriceUnitId(Identity $basePriceUnitId): self
    {
        $this->basePriceUnitId = $basePriceUnitId;

        return $this;
    }

    /**
     * @return Identity Reference to manufacturer
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getManufacturerId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->manufacturerId);
    }

    /**
     * @param Identity $manufacturerId Reference to manufacturer
     *
     * @return $this
     */
    public function setManufacturerId(Identity $manufacturerId): self
    {
        $this->manufacturerId = $manufacturerId;

        return $this;
    }

    /**
     * @return Identity Reference to master product
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getMasterProductId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->masterProductId);
    }

    /**
     * @param Identity $masterProductId Reference to master product
     *
     * @return $this
     */
    public function setMasterProductId(Identity $masterProductId): self
    {
        $this->masterProductId = $masterProductId;

        return $this;
    }

    /**
     * @return Identity Optional reference to measurement unit id
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getMeasurementUnitId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->measurementUnitId);
    }

    /**
     * @param Identity $measurementUnitId Optional reference to measurement unit id
     *
     * @return $this
     */
    public function setMeasurementUnitId(Identity $measurementUnitId): self
    {
        $this->measurementUnitId = $measurementUnitId;

        return $this;
    }

    /**
     * @return Identity Optional reference to partsList
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getPartsListId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->partsListId);
    }

    /**
     * @param Identity $partsListId Optional reference to partsList
     *
     * @return $this
     */
    public function setPartsListId(Identity $partsListId): self
    {
        $this->partsListId = $partsListId;

        return $this;
    }

    /**
     * @return Identity Optional reference to productType
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getProductTypeId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->productTypeId);
    }

    /**
     * @param Identity $productTypeId Optional reference to productType
     *
     * @return $this
     */
    public function setProductTypeId(Identity $productTypeId): self
    {
        $this->productTypeId = $productTypeId;

        return $this;
    }

    /**
     * @return Identity Reference to shippingClass
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getShippingClassId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->shippingClassId);
    }

    /**
     * @param Identity $shippingClassId Reference to shippingClass
     *
     * @return $this
     */
    public function setShippingClassId(Identity $shippingClassId): self
    {
        $this->shippingClassId = $shippingClassId;

        return $this;
    }

    /**
     * @return Identity|null
     */
    public function getTaxClassId(): ?Identity
    {
        return $this->taxClassId;
    }

    /**
     * @param Identity $taxClassId
     *
     * @return $this
     */
    public function setTaxClassId(Identity $taxClassId): self
    {
        $this->taxClassId = $taxClassId;

        return $this;
    }

    /**
     * @return Identity Reference to unit
     * @throws MustNotBeNullException
     * @throws TypeError
     */
    public function getUnitId(): Identity
    {
        return Validate::checkIdentityAndNotNull($this->unitId);
    }

    /**
     * @param Identity $unitId Reference to unit
     *
     * @return $this
     */
    public function setUnitId(Identity $unitId): self
    {
        $this->unitId = $unitId;

        return $this;
    }

    /**
     * @return string Optional Amazon Standard Identification Number
     */
    public function getAsin(): string
    {
        return $this->asin;
    }

    /**
     * @param string $asin Optional Amazon Standard Identification Number
     *
     * @return $this
     */
    public function setAsin(string $asin): self
    {
        $this->asin = $asin;

        return $this;
    }

    /**
     * @return \DateTimeInterface Optional available from date. Specify a date, upon when product can be purchased.
     */
    public function getAvailableFrom(): ?\DateTimeInterface
    {
        return $this->availableFrom;
    }

    /**
     * @param \DateTimeInterface|null $availableFrom Optional available from date.
     *                                               Specify a date, upon when product can be purchased.
     *
     * @return $this
     */
    public function setAvailableFrom(?\DateTimeInterface $availableFrom = null): self
    {
        $this->availableFrom = $availableFrom;

        return $this;
    }

    /**
     * @return float Optional base price divisor.
     *                Calculate basePriceDivisor by dividing product filling quantity through unit pricing base measure.
     *                E.g. 75ml / 100ml = 0.75
     */
    public function getBasePriceDivisor(): float
    {
        return $this->basePriceDivisor;
    }

    /**
     * @param float $basePriceDivisor Optional base price divisor.
     *                                Calculate basePriceDivisor by dividing product filling quantity through
     *                                unit pricing base measure. E.g. 75ml / 100ml = 0.75
     *
     * @return $this
     */
    public function setBasePriceDivisor(float $basePriceDivisor): self
    {
        $this->basePriceDivisor = $basePriceDivisor;

        return $this;
    }

    /**
     * @return float
     */
    public function getBasePriceFactor(): float
    {
        return $this->basePriceFactor;
    }

    /**
     * @param float $basePriceFactor
     *
     * @return $this
     */
    public function setBasePriceFactor(float $basePriceFactor): self
    {
        $this->basePriceFactor = $basePriceFactor;

        return $this;
    }

    /**
     * @return float Optional base price quantity
     */
    public function getBasePriceQuantity(): float
    {
        return $this->basePriceQuantity;
    }

    /**
     * @param float $basePriceQuantity Optional base price quantity
     *
     * @return $this
     */
    public function setBasePriceQuantity(float $basePriceQuantity): self
    {
        $this->basePriceQuantity = $basePriceQuantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePriceUnitCode(): string
    {
        return $this->basePriceUnitCode;
    }

    /**
     * @param string $basePriceUnitCode
     *
     * @return $this
     */
    public function setBasePriceUnitCode(string $basePriceUnitCode): self
    {
        $this->basePriceUnitCode = $basePriceUnitCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePriceUnitName(): string
    {
        return $this->basePriceUnitName;
    }

    /**
     * @param string $basePriceUnitName
     *
     * @return $this
     */
    public function setBasePriceUnitName(string $basePriceUnitName): self
    {
        $this->basePriceUnitName = $basePriceUnitName;

        return $this;
    }

    /**
     * @return bool Optional: Set to true to display base price / unit pricing measure
     */
    public function getConsiderBasePrice(): bool
    {
        return $this->considerBasePrice;
    }

    /**
     * @param bool $considerBasePrice Optional: Set to true to display base price / unit pricing measure
     *
     * @return $this
     */
    public function setConsiderBasePrice(bool $considerBasePrice): self
    {
        $this->considerBasePrice = $considerBasePrice;

        return $this;
    }

    /**
     * @return bool Consider stock level?
     *                  If true, product can only be purchased with a positive stockLevel or when permitNegativeStock
     *                  is set to true
     */
    public function getConsiderStock(): bool
    {
        return $this->considerStock;
    }

    /**
     * @param bool $considerStock Consider stock level?
     *                            If true, product can only be purchased with a positive stockLevel or when
     *                            permitNegativeStock is set to true
     *
     * @return $this
     */
    public function setConsiderStock(bool $considerStock): self
    {
        $this->considerStock = $considerStock;

        return $this;
    }

    /**
     * @return bool Optional: Consider stock levels of productVariations. Same as considerStock but for variations.
     */
    public function getConsiderVariationStock(): bool
    {
        return $this->considerVariationStock;
    }

    /**
     * @param bool $considerVariationStock Optional: Consider stock levels of productVariations.
     *                                     Same as considerStock but for variations.
     *
     * @return $this
     */
    public function setConsiderVariationStock(bool $considerVariationStock): self
    {
        $this->considerVariationStock = $considerVariationStock;

        return $this;
    }

    /**
     * @return DateTimeInterface|null Creation date
     * @throws TypeError
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return Validate::checkDateTimeInterfaceOrNull($this->creationDate);
    }

    /**
     * @param \DateTimeInterface|null $creationDate Creation date
     *
     * @return $this
     */
    public function setCreationDate(?\DateTimeInterface $creationDate = null): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDiscountable(): bool
    {
        return $this->discountable;
    }

    /**
     * @param bool $discountable
     *
     * @return $this
     */
    public function setDiscountable(bool $discountable): self
    {
        $this->discountable = $discountable;

        return $this;
    }

    /**
     * @return string Optional European Article Number (EAN)
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @param string $ean Optional European Article Number (EAN)
     *
     * @return $this
     */
    public function setEan(string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * @return string Optional Ebay product ID
     */
    public function getEpId(): string
    {
        return $this->epid;
    }

    /**
     * @param string $epid Optional Ebay product ID
     *
     * @return $this
     */
    public function setEpid(string $epid): self
    {
        $this->epid = $epid;

        return $this;
    }

    /**
     * @return string Optional Hazard identifier, encodes general hazard class und subdivision
     */
    public function getHazardIdNumber(): string
    {
        return $this->hazardIdNumber;
    }

    /**
     * @param string $hazardIdNumber Optional Hazard identifier, encodes general hazard class und subdivision
     *
     * @return $this
     */
    public function setHazardIdNumber(string $hazardIdNumber): self
    {
        $this->hazardIdNumber = $hazardIdNumber;

        return $this;
    }

    /**
     * @return float Optional product height
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height Optional product height
     *
     * @return $this
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsBatch(): bool
    {
        return $this->isBatch;
    }

    /**
     * @param bool $isBatch
     *
     * @return $this
     */
    public function setIsBatch(bool $isBatch): self
    {
        $this->isBatch = $isBatch;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsBestBefore(): bool
    {
        return $this->isBestBefore;
    }

    /**
     * @param bool $isBestBefore
     *
     * @return $this
     */
    public function setIsBestBefore(bool $isBestBefore): self
    {
        $this->isBestBefore = $isBestBefore;

        return $this;
    }

    /**
     * @return string Optional International Standard Book Number
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn Optional International Standard Book Number
     *
     * @return $this
     */
    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return bool Optional: Set to true to allow non-integer quantites for purchase
     */
    public function getIsDivisible(): bool
    {
        return $this->isDivisible;
    }

    /**
     * @param bool $isDivisible Optional: Set to true to allow non-integer quantites for purchase
     *
     * @return $this
     */
    public function setIsDivisible(bool $isDivisible): self
    {
        $this->isDivisible = $isDivisible;

        return $this;
    }

    /**
     * @return bool Optional flag if product is master product
     */
    public function getIsMasterProduct(): bool
    {
        return $this->isMasterProduct;
    }

    /**
     * @param bool $isMasterProduct Optional flag if product is master product
     *
     * @return $this
     */
    public function setIsMasterProduct(bool $isMasterProduct): self
    {
        $this->isMasterProduct = $isMasterProduct;

        return $this;
    }

    /**
     * @return bool Optional flag new product.
     *                  If true, product will be highlighted as new (creation date may also be considered)
     */
    public function getIsNewProduct(): bool
    {
        return $this->isNewProduct;
    }

    /**
     * @param bool $isNewProduct Optional flag new product.
     *                           If true, product will be highlighted as new (creation date may also be considered)
     *
     * @return $this
     */
    public function setIsNewProduct(bool $isNewProduct): self
    {
        $this->isNewProduct = $isNewProduct;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSerialNumber(): bool
    {
        return $this->isSerialNumber;
    }

    /**
     * @param bool $isSerialNumber
     *
     * @return $this
     */
    public function setIsSerialNumber(bool $isSerialNumber): self
    {
        $this->isSerialNumber = $isSerialNumber;

        return $this;
    }

    /**
     * @return bool Optional flag top product.
     *                  If true, product will be highlighted as top product (e.g. in product lists or in special boxes)
     */
    public function getIsTopProduct(): bool
    {
        return $this->isTopProduct;
    }

    /**
     * @param bool $isTopProduct Optional flag top product.
     *                           If true, product will be highlighted as top product
     *                           (e.g. in product lists or in special boxes)
     *
     * @return $this
     */
    public function setIsTopProduct(bool $isTopProduct): self
    {
        $this->isTopProduct = $isTopProduct;

        return $this;
    }

    /**
     * @return string Optional internal keywords and synonyms for product
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords Optional internal keywords and synonyms for product
     *
     * @return $this
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return float Optional product length
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param float $length Optional product length
     *
     * @return $this
     */
    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string Optional manufacturer number
     */
    public function getManufacturerNumber(): string
    {
        return $this->manufacturerNumber;
    }

    /**
     * @param string $manufacturerNumber Optional manufacturer number
     *
     * @return $this
     */
    public function setManufacturerNumber(string $manufacturerNumber): self
    {
        $this->manufacturerNumber = $manufacturerNumber;

        return $this;
    }

    /**
     * @return Manufacturer|null Optional manufacturer
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    /**
     * @param Manufacturer|null $manufacturer Optional manufacturer
     *
     * @return $this
     */
    public function setManufacturer(?Manufacturer $manufacturer = null): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return float Optional measurement quantity
     */
    public function getMeasurementQuantity(): float
    {
        return $this->measurementQuantity;
    }

    /**
     * @param float $measurementQuantity Optional measurement quantity
     *
     * @return $this
     */
    public function setMeasurementQuantity(float $measurementQuantity): self
    {
        $this->measurementQuantity = $measurementQuantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeasurementUnitCode(): string
    {
        return $this->measurementUnitCode;
    }

    /**
     * @param string $measurementUnitCode
     *
     * @return $this
     */
    public function setMeasurementUnitCode(string $measurementUnitCode): self
    {
        $this->measurementUnitCode = $measurementUnitCode;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getMinBestBeforeDate(): ?\DateTimeInterface
    {
        return $this->minBestBeforeDate;
    }

    /**
     * @param \DateTimeInterface|null $minBestBeforeDate
     *
     * @return $this
     */
    public function setMinBestBeforeDate(?\DateTimeInterface $minBestBeforeDate = null): self
    {
        $this->minBestBeforeDate = $minBestBeforeDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinimumOrderQuantity(): float
    {
        return $this->minimumOrderQuantity;
    }

    /**
     * @param float $minimumOrderQuantity
     *
     * @return $this
     */
    public function setMinimumOrderQuantity(float $minimumOrderQuantity): self
    {
        $this->minimumOrderQuantity = $minimumOrderQuantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinimumQuantity(): float
    {
        return $this->minimumQuantity;
    }

    /**
     * @param float $minimumQuantity
     *
     * @return $this
     */
    public function setMinimumQuantity(float $minimumQuantity): self
    {
        $this->minimumQuantity = $minimumQuantity;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * @param \DateTimeInterface|null $modified
     *
     * @return $this
     */
    public function setModified(?\DateTimeInterface $modified = null): self
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getNewReleaseDate(): ?\DateTimeInterface
    {
        return $this->newReleaseDate;
    }

    /**
     * @param \DateTimeInterface|null $newReleaseDate
     *
     * @return $this
     */
    public function setNewReleaseDate(?\DateTimeInterface $newReleaseDate = null): self
    {
        $this->newReleaseDate = $newReleaseDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface Contains the date of the next available inflow.
     */
    public function getNextAvailableInflowDate(): ?\DateTimeInterface
    {
        return $this->nextAvailableInflowDate;
    }

    /**
     * @param \DateTimeInterface|null $nextAvailableInflowDate Contains the date of the next available inflow.
     *
     * @return $this
     */
    public function setNextAvailableInflowDate(?\DateTimeInterface $nextAvailableInflowDate = null): self
    {
        $this->nextAvailableInflowDate = $nextAvailableInflowDate;

        return $this;
    }

    /**
     * @return float Contains the quantity of the next available inflow.
     */
    public function getNextAvailableInflowQuantity(): float
    {
        return $this->nextAvailableInflowQuantity;
    }

    /**
     * @param float $nextAvailableInflowQuantity Contains the quantity of the next available inflow.
     *
     * @return $this
     */
    public function setNextAvailableInflowQuantity(float $nextAvailableInflowQuantity): self
    {
        $this->nextAvailableInflowQuantity = $nextAvailableInflowQuantity;

        return $this;
    }

    /**
     * @return string Optional internal product note
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note Optional internal product note
     *
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string Optional Origin country
     */
    public function getOriginCountry(): string
    {
        return $this->originCountry;
    }

    /**
     * @param string $originCountry Optional Origin country
     *
     * @return $this
     */
    public function setOriginCountry(string $originCountry): self
    {
        $this->originCountry = $originCountry;

        return $this;
    }

    /**
     * @return float Optional: self can only be purchased in multiples of takeOffQuantity e.g. 5,10,15...
     */
    public function getPackagingQuantity(): float
    {
        return $this->packagingQuantity;
    }

    /**
     * @param float $packagingQuantity Optional:
     *                                 self can only be purchased in multiples of takeOffQuantity e.g. 5,10,15...
     *
     * @return $this
     */
    public function setPackagingQuantity(float $packagingQuantity): self
    {
        $this->packagingQuantity = $packagingQuantity;

        return $this;
    }

    /**
     * @return bool Optional Permit negative stock / allow overselling.
     *                  If true, product can be purchased even
     *                  if stockLevel is less or equal 0 and considerStock is true.
     */
    public function getPermitNegativeStock(): bool
    {
        return $this->permitNegativeStock;
    }

    /**
     * @param bool $permitNegativeStock Optional Permit negative stock / allow overselling.
     *                                  If true, product can be purchased even if stockLevel is less or equal 0
     *                                  and considerStock is true.
     *
     * @return $this
     */
    public function setPermitNegativeStock(bool $permitNegativeStock): self
    {
        $this->permitNegativeStock = $permitNegativeStock;

        return $this;
    }

    /**
     * @return float Productweight exclusive packaging
     */
    public function getProductWeight(): float
    {
        return $this->productWeight;
    }

    /**
     * @param float $productWeight Productweight exclusive packaging
     *
     * @return $this
     */
    public function setProductWeight(float $productWeight): self
    {
        $this->productWeight = $productWeight;

        return $this;
    }

    /**
     * @return float
     */
    public function getPurchasePrice(): float
    {
        return $this->purchasePrice;
    }

    /**
     * @param float $purchasePrice
     *
     * @return $this
     */
    public function setPurchasePrice(float $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * @return float Optional recommended retail price (gross)
     */
    public function getRecommendedRetailPrice(): float
    {
        return $this->recommendedRetailPrice;
    }

    /**
     * @param float $recommendedRetailPrice Optional recommended retail price (gross)
     *
     * @return $this
     */
    public function setRecommendedRetailPrice(float $recommendedRetailPrice): self
    {
        $this->recommendedRetailPrice = $recommendedRetailPrice;

        return $this;
    }

    /**
     * @return string Optional serial number
     */
    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber Optional serial number
     *
     * @return $this
     */
    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * @return float Productweight inclusive packaging
     */
    public function getShippingWeight(): float
    {
        return $this->shippingWeight;
    }

    /**
     * @param float $shippingWeight Productweight inclusive packaging
     *
     * @return $this
     */
    public function setShippingWeight(float $shippingWeight): self
    {
        $this->shippingWeight = $shippingWeight;

        return $this;
    }

    /**
     * @return string Optional stock keeping unit identifier
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku Optional stock keeping unit identifier
     *
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->setIdentificationStringBySubject('sku', \sprintf('SKU = %s', $sku));
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int Optional sort number for product sorting in lists
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort Optional sort number for product sorting in lists
     *
     * @return $this
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

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

    /**
     * @return float
     */
    public function getSupplierStockLevel(): float
    {
        return $this->supplierStockLevel;
    }

    /**
     * @param float $supplierStockLevel
     *
     * @return $this
     */
    public function setSupplierStockLevel(float $supplierStockLevel): self
    {
        $this->supplierStockLevel = $supplierStockLevel;

        return $this;
    }

    /**
     * @return string Optional TARIC
     */
    public function getTaric(): string
    {
        return $this->taric;
    }

    /**
     * @param string $taric Optional TARIC
     *
     * @return $this
     */
    public function setTaric(string $taric): self
    {
        $this->taric = $taric;

        return $this;
    }

    /**
     * @return string Optional UN number, used to define hazardous properties
     */
    public function getUnNumber(): string
    {
        return $this->unNumber;
    }

    /**
     * @param string $unNumber Optional UN number, used to define hazardous properties
     *
     * @return $this
     */
    public function setUnNumber(string $unNumber): self
    {
        $this->unNumber = $unNumber;

        return $this;
    }

    /**
     * @return string Optional Universal Product Code
     */
    public function getUpc(): string
    {
        return $this->upc;
    }

    /**
     * @param string $upc Optional Universal Product Code
     *
     * @return $this
     */
    public function setUpc(string $upc): self
    {
        $this->upc = $upc;

        return $this;
    }

    /**
     * @return float
     */
    public function getVat(): float
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     *
     * @return $this
     */
    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return float Optional product width
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width Optional product width
     *
     * @return $this
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param ProductAttribute $attribute
     *
     * @return $this
     */
    public function addAttribute(ProductAttribute $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * @return ProductAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @phpstan-param ProductAttribute ...$attributes
     *
     * @param TranslatableAttribute ...$attributes
     *
     * @return $this
     */
    public function setAttributes(TranslatableAttribute ...$attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAttributes(): self
    {
        $this->attributes = [];

        return $this;
    }

    /**
     * @param Product2Category $category
     *
     * @return $this
     */
    public function addCategory(Product2Category $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @return Product2Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Product2Category ...$categories
     *
     * @return $this
     */
    public function setCategories(Product2Category ...$categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCategories(): self
    {
        $this->categories = [];

        return $this;
    }

    /**
     * @param ProductChecksum $checksum
     *
     * @return $this
     */
    public function addChecksum(ProductChecksum $checksum): self
    {
        $this->checksums[] = $checksum;

        return $this;
    }

    /**
     * @return ProductChecksum[]
     */
    public function getChecksums(): array
    {
        return $this->checksums;
    }

    /**
     * @param ProductChecksum ...$checksums
     *
     * @return $this
     */
    public function setChecksums(ProductChecksum ...$checksums): self
    {
        $this->checksums = $checksums;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearChecksums(): self
    {
        $this->checksums = [];

        return $this;
    }

    /**
     * @param ProductConfigGroup $configGroup
     *
     * @return $this
     */
    public function addConfigGroup(ProductConfigGroup $configGroup): self
    {
        $this->configGroups[] = $configGroup;

        return $this;
    }

    /**
     * @return ProductConfigGroup[]
     */
    public function getConfigGroups(): array
    {
        return $this->configGroups;
    }

    /**
     * @param ProductConfigGroup ...$configGroups
     *
     * @return $this
     */
    public function setConfigGroups(ProductConfigGroup ...$configGroups): self
    {
        $this->configGroups = $configGroups;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearConfigGroups(): self
    {
        $this->configGroups = [];

        return $this;
    }

    /**
     * @param CustomerGroupPackagingQuantity $customerGroupPackagingQuantity
     *
     * @return $this
     */
    public function addCustomerGroupPackagingQuantity(
        CustomerGroupPackagingQuantity $customerGroupPackagingQuantity
    ): self {
        $this->customerGroupPackagingQuantities[] = $customerGroupPackagingQuantity;

        return $this;
    }

    /**
     * @return CustomerGroupPackagingQuantity[]
     */
    public function getCustomerGroupPackagingQuantities(): array
    {
        return $this->customerGroupPackagingQuantities;
    }

    /**
     * @param CustomerGroupPackagingQuantity ...$customerGroupPackagingQuantities
     *
     * @return $this
     */
    public function setCustomerGroupPackagingQuantities(
        CustomerGroupPackagingQuantity ...$customerGroupPackagingQuantities
    ): self {
        $this->customerGroupPackagingQuantities = $customerGroupPackagingQuantities;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCustomerGroupPackagingQuantities(): self
    {
        $this->customerGroupPackagingQuantities = [];

        return $this;
    }

    /**
     * @param ProductFileDownload $fileDownload
     *
     * @return $this
     */
    public function addFileDownload(ProductFileDownload $fileDownload): self
    {
        $this->fileDownloads[] = $fileDownload;

        return $this;
    }

    /**
     * @return ProductFileDownload[]
     */
    public function getFileDownloads(): array
    {
        return $this->fileDownloads;
    }

    /**
     * @param ProductFileDownload ...$fileDownloads
     *
     * @return $this
     */
    public function setFileDownloads(ProductFileDownload ...$fileDownloads): self
    {
        $this->fileDownloads = $fileDownloads;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearFileDownloads(): self
    {
        $this->fileDownloads = [];

        return $this;
    }

    /**
     * @param ProductI18n $i18n
     *
     * @return $this
     */
    public function addI18n(ProductI18n $i18n): self
    {
        $this->i18ns[] = $i18n;

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
     * @param ProductInvisibility $invisibility
     *
     * @return $this
     */
    public function addInvisibility(ProductInvisibility $invisibility): self
    {
        $this->invisibilities[] = $invisibility;

        return $this;
    }

    /**
     * @return ProductInvisibility[]
     */
    public function getInvisibilities(): array
    {
        return $this->invisibilities;
    }

    /**
     * @param ProductInvisibility ...$invisibilities
     *
     * @return $this
     */
    public function setInvisibilities(ProductInvisibility ...$invisibilities): self
    {
        $this->invisibilities = $invisibilities;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearInvisibilities(): self
    {
        $this->invisibilities = [];

        return $this;
    }

    /**
     * @param ProductMediaFile $mediaFile
     *
     * @return $this
     */
    public function addMediaFile(ProductMediaFile $mediaFile): self
    {
        $this->mediaFiles[] = $mediaFile;

        return $this;
    }

    /**
     * @return ProductMediaFile[]
     */
    public function getMediaFiles(): array
    {
        return $this->mediaFiles;
    }

    /**
     * @param ProductMediaFile ...$mediaFiles
     *
     * @return $this
     */
    public function setMediaFiles(ProductMediaFile ...$mediaFiles): self
    {
        $this->mediaFiles = $mediaFiles;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearMediaFiles(): self
    {
        $this->mediaFiles = [];

        return $this;
    }

    /**
     * @param ProductPartsList $partsList
     *
     * @return $this
     */
    public function addPartsList(ProductPartsList $partsList): self
    {
        $this->partsLists[] = $partsList;

        return $this;
    }

    /**
     * @return ProductPartsList[]
     */
    public function getPartsLists(): array
    {
        return $this->partsLists;
    }

    /**
     * @param ProductPartsList ...$partsLists
     *
     * @return $this
     */
    public function setPartsLists(ProductPartsList ...$partsLists): self
    {
        $this->partsLists = $partsLists;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearPartsLists(): self
    {
        $this->partsLists = [];

        return $this;
    }

    /**
     * @param ProductPrice $price
     *
     * @return $this
     */
    public function addPrice(ProductPrice $price): self
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * @return ProductPrice[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param ProductPrice ...$prices
     *
     * @return $this
     */
    public function setPrices(ProductPrice ...$prices): self
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

    /**
     * @param ProductSpecialPrice $specialPrice
     *
     * @return $this
     */
    public function addSpecialPrice(ProductSpecialPrice $specialPrice): self
    {
        $this->specialPrices[] = $specialPrice;

        return $this;
    }

    /**
     * @return ProductSpecialPrice[]
     */
    public function getSpecialPrices(): array
    {
        return $this->specialPrices;
    }

    /**
     * @param ProductSpecialPrice ...$specialPrices
     *
     * @return $this
     */
    public function setSpecialPrices(ProductSpecialPrice ...$specialPrices): self
    {
        $this->specialPrices = $specialPrices;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearSpecialPrices(): self
    {
        $this->specialPrices = [];

        return $this;
    }

    /**
     * @param ProductSpecific $specific
     *
     * @return $this
     */
    public function addSpecific(ProductSpecific $specific): self
    {
        $this->specifics[] = $specific;

        return $this;
    }

    /**
     * @return ProductSpecific[]
     */
    public function getSpecifics(): array
    {
        return $this->specifics;
    }

    /**
     * @param ProductSpecific ...$specifics
     *
     * @return $this
     */
    public function setSpecifics(ProductSpecific ...$specifics): self
    {
        $this->specifics = $specifics;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearSpecifics(): self
    {
        $this->specifics = [];

        return $this;
    }

    /**
     * @param string $countryIso
     *
     * @return TaxRate|null
     */
    public function findTaxRateByCountryIso(string $countryIso): ?TaxRate
    {
        foreach ($this->taxRates as $taxRate) {
            if ($countryIso === $taxRate->getCountryIso()) {
                return $taxRate;
            }
        }

        return null;
    }

    /**
     * @return array<TaxRate>
     */
    public function getTaxRates(): array
    {
        return $this->taxRates;
    }

    /**
     * @param TaxRate ...$taxRates
     *
     * @return $this
     */
    public function setTaxRates(TaxRate ...$taxRates): self
    {
        $this->taxRates = $taxRates;

        return $this;
    }

    /**
     * @param ProductVariation $variation
     *
     * @return $this
     */
    public function addVariation(ProductVariation $variation): self
    {
        $this->variations[] = $variation;

        return $this;
    }

    /**
     * @return ProductVariation[]
     */
    public function getVariations(): array
    {
        return $this->variations;
    }

    /**
     * @param ProductVariation ...$variations
     *
     * @return $this
     */
    public function setVariations(ProductVariation ...$variations): self
    {
        $this->variations = $variations;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearVariations(): self
    {
        $this->variations = [];

        return $this;
    }

    /**
     * @param ProductWarehouseInfo $warehouseInfo
     *
     * @return $this
     */
    public function addWarehouseInfo(ProductWarehouseInfo $warehouseInfo): self
    {
        $this->warehouseInfo[] = $warehouseInfo;

        return $this;
    }

    /**
     * @return ProductWarehouseInfo[]
     */
    public function getWarehouseInfo(): array
    {
        return $this->warehouseInfo;
    }

    /**
     * @param ProductWarehouseInfo ...$warehouseInfo
     *
     * @return $this
     */
    public function setWarehouseInfo(ProductWarehouseInfo ...$warehouseInfo): self
    {
        $this->warehouseInfo = $warehouseInfo;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearWarehouseInfo(): self
    {
        $this->warehouseInfo = [];

        return $this;
    }

    /**
     * @return int
     */
    public function calculateHandlingTime(): int
    {
        $handlingTime = $this->getAdditionalHandlingTime();
        if ($this->stockLevel <= 0.) {
            $handlingTime += $this->getSupplierDeliveryTime();
        }

        return $handlingTime;
    }

    /**
     * @return int
     */
    public function getAdditionalHandlingTime(): int
    {
        return $this->additionalHandlingTime;
    }

    /**
     * @param int $additionalHandlingTime
     *
     * @return $this
     */
    public function setAdditionalHandlingTime(int $additionalHandlingTime): self
    {
        $this->additionalHandlingTime = $additionalHandlingTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getSupplierDeliveryTime(): int
    {
        return $this->supplierDeliveryTime;
    }

    /**
     * @param int $supplierDeliveryTime
     *
     * @return $this
     */
    public function setSupplierDeliveryTime(int $supplierDeliveryTime): self
    {
        $this->supplierDeliveryTime = $supplierDeliveryTime;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIdentificationStrings(): array
    {
        $this->unsetIdentificationStringBySubject('name');
        foreach ($this->getI18ns() as $i18n) {
            if ($i18n->getName() !== '') {
                $this->setIdentificationStringBySubject('name', \sprintf('Name = %s', $i18n->getName()));
                break;
            }
        }

        return parent::getIdentificationStrings();
    }

    /**
     * @return ProductI18n[]
     */
    public function getI18ns(): array
    {
        return $this->i18ns;
    }

    /**
     * @param ProductI18n ...$i18ns
     *
     * @return $this
     */
    public function setI18ns(ProductI18n ...$i18ns): self
    {
        $this->i18ns = $i18ns;

        return $this;
    }
}

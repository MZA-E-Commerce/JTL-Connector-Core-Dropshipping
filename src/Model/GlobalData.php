<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class GlobalData extends AbstractModel
{
    /** @var ConfigGroup[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ConfigGroup>')]
    #[Serializer\SerializedName('configGroups')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $configGroups = [];

    /** @var ConfigItem[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ConfigItem>')]
    #[Serializer\SerializedName('configItems')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $configItems = [];

    /** @var CrossSellingGroup[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CrossSellingGroup>')]
    #[Serializer\SerializedName('crossSellingGroups')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $crossSellingGroups = [];

    /** @var Currency[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Currency>')]
    #[Serializer\SerializedName('currencies')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $currencies = [];

    /** @var CustomerGroup[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\CustomerGroup>')]
    #[Serializer\SerializedName('customerGroups')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $customerGroups = [];

    /** @var Language[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Language>')]
    #[Serializer\SerializedName('languages')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $languages = [];

    /** @var MeasurementUnit[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\MeasurementUnit>')]
    #[Serializer\SerializedName('measurementUnits')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $measurementUnits = [];

    /** @var ProductType[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ProductType>')]
    #[Serializer\SerializedName('productTypes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $productTypes = [];

    /** @var ShippingClass[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ShippingClass>')]
    #[Serializer\SerializedName('shippingClasses')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $shippingClasses = [];

    /** @var ShippingMethod[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\ShippingMethod>')]
    #[Serializer\SerializedName('shippingMethods')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $shippingMethods = [];

    /** @var TaxRate[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\TaxRate>')]
    #[Serializer\SerializedName('taxRates')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $taxRates = [];

    /** @var Unit[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Unit>')]
    #[Serializer\SerializedName('units')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $units = [];

    /** @var Warehouse[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\Warehouse>')]
    #[Serializer\SerializedName('warehouses')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $warehouses = [];


    /**
     * @param ConfigGroup $configGroup
     *
     * @return $this
     */
    public function addConfigGroup(ConfigGroup $configGroup): self
    {
        $this->configGroups[] = $configGroup;

        return $this;
    }

    /**
     * @return ConfigGroup[]
     */
    public function getConfigGroups(): array
    {
        return $this->configGroups;
    }

    /**
     * @param ConfigGroup ...$configGroups
     *
     * @return $this
     */
    public function setConfigGroups(ConfigGroup ...$configGroups): self
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
     * @param ConfigItem $configItem
     *
     * @return $this
     */
    public function addConfigItem(ConfigItem $configItem): self
    {
        $this->configItems[] = $configItem;

        return $this;
    }

    /**
     * @return ConfigItem[]
     */
    public function getConfigItems(): array
    {
        return $this->configItems;
    }

    /**
     * @param ConfigItem ...$configItems
     *
     * @return $this
     */
    public function setConfigItems(ConfigItem ...$configItems): self
    {
        $this->configItems = $configItems;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearConfigItems(): self
    {
        $this->configItems = [];

        return $this;
    }

    /**
     * @param CrossSellingGroup $crossSellingGroup
     *
     * @return $this
     */
    public function addCrossSellingGroup(CrossSellingGroup $crossSellingGroup): self
    {
        $this->crossSellingGroups[] = $crossSellingGroup;

        return $this;
    }

    /**
     * @return CrossSellingGroup[]
     */
    public function getCrossSellingGroups(): array
    {
        return $this->crossSellingGroups;
    }

    /**
     * @param CrossSellingGroup ...$crossSellingGroups
     *
     * @return $this
     */
    public function setCrossSellingGroups(CrossSellingGroup ...$crossSellingGroups): self
    {
        $this->crossSellingGroups = $crossSellingGroups;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCrossSellingGroups(): self
    {
        $this->crossSellingGroups = [];

        return $this;
    }

    /**
     * @param Currency $currency
     *
     * @return $this
     */
    public function addCurrency(Currency $currency): self
    {
        $this->currencies[] = $currency;

        return $this;
    }

    /**
     * @return Currency[]
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * @param Currency ...$currencies
     *
     * @return $this
     */
    public function setCurrencies(Currency ...$currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCurrencies(): self
    {
        $this->currencies = [];

        return $this;
    }

    /**
     * @param CustomerGroup $customerGroup
     *
     * @return $this
     */
    public function addCustomerGroup(CustomerGroup $customerGroup): self
    {
        $this->customerGroups[] = $customerGroup;

        return $this;
    }

    /**
     * @return CustomerGroup[]
     */
    public function getCustomerGroups(): array
    {
        return $this->customerGroups;
    }

    /**
     * @param CustomerGroup ...$customerGroups
     *
     * @return $this
     */
    public function setCustomerGroups(CustomerGroup ...$customerGroups): self
    {
        $this->customerGroups = $customerGroups;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCustomerGroups(): self
    {
        $this->customerGroups = [];

        return $this;
    }

    /**
     * @param Language $language
     *
     * @return $this
     */
    public function addLanguage(Language $language): self
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param Language ...$languages
     *
     * @return $this
     */
    public function setLanguages(Language ...$languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearLanguages(): self
    {
        $this->languages = [];

        return $this;
    }

    /**
     * @param MeasurementUnit $measurementUnit
     *
     * @return $this
     */
    public function addMeasurementUnit(MeasurementUnit $measurementUnit): self
    {
        $this->measurementUnits[] = $measurementUnit;

        return $this;
    }

    /**
     * @return MeasurementUnit[]
     */
    public function getMeasurementUnits(): array
    {
        return $this->measurementUnits;
    }

    /**
     * @param MeasurementUnit ...$measurementUnits
     *
     * @return $this
     */
    public function setMeasurementUnits(MeasurementUnit ...$measurementUnits): self
    {
        $this->measurementUnits = $measurementUnits;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearMeasurementUnits(): self
    {
        $this->measurementUnits = [];

        return $this;
    }

    /**
     * @param ProductType $productType
     *
     * @return $this
     */
    public function addProductType(ProductType $productType): self
    {
        $this->productTypes[] = $productType;

        return $this;
    }

    /**
     * @return ProductType[]
     */
    public function getProductTypes(): array
    {
        return $this->productTypes;
    }

    /**
     * @param ProductType ...$productTypes
     *
     * @return $this
     */
    public function setProductTypes(ProductType ...$productTypes): self
    {
        $this->productTypes = $productTypes;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearProductTypes(): self
    {
        $this->productTypes = [];

        return $this;
    }

    /**
     * @param ShippingClass $shippingClass
     *
     * @return $this
     */
    public function addShippingClass(ShippingClass $shippingClass): self
    {
        $this->shippingClasses[] = $shippingClass;

        return $this;
    }

    /**
     * @return ShippingClass[]
     */
    public function getShippingClasses(): array
    {
        return $this->shippingClasses;
    }

    /**
     * @param ShippingClass ...$shippingClasses
     *
     * @return $this
     */
    public function setShippingClasses(ShippingClass ...$shippingClasses): self
    {
        $this->shippingClasses = $shippingClasses;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearShippingClasses(): self
    {
        $this->shippingClasses = [];

        return $this;
    }

    /**
     * @param ShippingMethod $shippingMethod
     *
     * @return $this
     */
    public function addShippingMethod(ShippingMethod $shippingMethod): self
    {
        $this->shippingMethods[] = $shippingMethod;

        return $this;
    }

    /**
     * @return ShippingMethod[]
     */
    public function getShippingMethods(): array
    {
        return $this->shippingMethods;
    }

    /**
     * @param ShippingMethod ...$shippingMethods
     *
     * @return $this
     */
    public function setShippingMethods(ShippingMethod ...$shippingMethods): self
    {
        $this->shippingMethods = $shippingMethods;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearShippingMethods(): self
    {
        $this->shippingMethods = [];

        return $this;
    }

    /**
     * @param TaxRate $taxRate
     *
     * @return $this
     */
    public function addTaxRate(TaxRate $taxRate): self
    {
        $this->taxRates[] = $taxRate;

        return $this;
    }

    /**
     * @return TaxRate[]
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
     * @return $this
     */
    public function clearTaxRates(): self
    {
        $this->taxRates = [];

        return $this;
    }

    /**
     * @param Unit $unit
     *
     * @return $this
     */
    public function addUnit(Unit $unit): self
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * @return Unit[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @param Unit ...$units
     *
     * @return $this
     */
    public function setUnits(Unit ...$units): self
    {
        $this->units = $units;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearUnits(): self
    {
        $this->units = [];

        return $this;
    }

    /**
     * @param Warehouse $warehouse
     *
     * @return $this
     */
    public function addWarehouse(Warehouse $warehouse): self
    {
        $this->warehouses[] = $warehouse;

        return $this;
    }

    /**
     * @return Warehouse[]
     */
    public function getWarehouses(): array
    {
        return $this->warehouses;
    }

    /**
     * @param Warehouse ...$warehouses
     *
     * @return $this
     */
    public function setWarehouses(Warehouse ...$warehouses): self
    {
        $this->warehouses = $warehouses;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearWarehouses(): self
    {
        $this->warehouses = [];

        return $this;
    }
}

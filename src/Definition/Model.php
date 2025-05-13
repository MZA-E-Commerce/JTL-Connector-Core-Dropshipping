<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

use Jtl\Connector\Core\Exception\DefinitionException;

final class Model
{
    public const
        MODEL_NAMESPACE = 'Jtl\\Connector\\Core\\Model';

    public const
        CATEGORY                             = 'Category',
        CATEGORY_ATTRIBUTE                   = 'CategoryAttribute',
        CATEGORY_IMAGE                       = 'CategoryImage',
        CONFIG_GROUP                         = 'ConfigGroup',
        CONFIG_GROUP_IMAGE                   = 'ConfigGroupImage',
        CONFIG_ITEM                          = 'ConfigItem',
        CROSS_SELLING                        = 'CrossSelling',
        CROSS_SELLING_GROUP                  = 'CrossSellingGroup',
        CROSS_SELLING_ITEM                   = 'CrossSellingItem',
        CURRENCY                             = 'Currency',
        CUSTOMER                             = 'Customer',
        CUSTOMER_GROUP                       = 'CustomerGroup',
        CUSTOMER_ORDER                       = 'CustomerOrder',
        CUSTOMER_ORDER_ITEM                  = 'CustomerOrderItem',
        DELIVERY_NOTE                        = 'DeliveryNote',
        DELIVERY_NOTE_ITEM                   = 'DeliveryNoteItem',
        FILE_UPLOAD                          = 'FileUpload',
        GLOBAL_DATA                          = 'GlobalData',
        LANGUAGE                             = 'Language',
        MANUFACTURER                         = 'Manufacturer',
        MANUFACTURER_IMAGE                   = 'ManufacturerImage',
        MEASUREMENT_UNIT                     = 'MeasurementUnit',
        PAYMENT                              = 'Payment',
        PRODUCT                              = 'Product',
        PRODUCT_ATTRIBUTE                    = 'ProductAttribute',
        PRODUCT_CONFIG_GROUP                 = 'ProductConfigGroup',
        PRODUCT_IMAGE                        = 'ProductImage',
        PRODUCT_PRICE                        = 'ProductPrice',
        PRODUCT_SPECIFIC                     = 'ProductSpecific',
        PRODUCT_STOCK_LEVEL                  = 'ProductStockLevel',
        PRODUCT_TO_CATEGORY                  = 'Product2Category',
        PRODUCT_TYPE                         = 'ProductType',
        PRODUCT_VARIATION                    = 'ProductVariation',
        PRODUCT_VARIATION_VALUE              = 'ProductVariationValue',
        PRODUCT_VARIATION_VALUE_EXTRA_CHARGE = 'ProductVariationValueExtraCharge',
        PRODUCT_VARIATION_VALUE_IMAGE        = 'ProductVariationValueImage',
        PRODUCT_VARIATION_VALUE_INVISIBILITY = 'ProductVariationValueInvisibility',
        PRODUCT_WAREHOUSE_INFO               = 'ProductWarehouseInfo',
        SHIPPING_CLASS                       = 'ShippingClass',
        SHIPPING_METHOD                      = 'ShippingMethod',
        SHIPMENT                             = 'Shipment',
        SPECIFIC                             = 'Specific',
        SPECIFIC_IMAGE                       = 'SpecificImage',
        SPECIFIC_VALUE                       = 'SpecificValue',
        SPECIFIC_VALUE_IMAGE                 = 'SpecificValueImage',
        STATUS_CHANGE                        = 'StatusChange',
        TAX_CLASS                            = 'TaxClass',
        TAX_RATE                             = 'TaxRate',
        UNIT                                 = 'Unit',
        WAREHOUSE                            = 'Warehouse';

    /** @var string[]|null */
    protected static ?array $models = null;

    /** @var array<string, int> */
    protected static array $mappings = [
        self::CATEGORY                      => IdentityType::CATEGORY,
        self::CATEGORY_ATTRIBUTE            => IdentityType::CATEGORY_ATTRIBUTE,
        self::CATEGORY_IMAGE                => IdentityType::CATEGORY_IMAGE,
        self::CONFIG_GROUP                  => IdentityType::CONFIG_GROUP,
        self::CONFIG_GROUP_IMAGE            => IdentityType::CONFIG_GROUP_IMAGE,
        self::CONFIG_ITEM                   => IdentityType::CONFIG_ITEM,
        self::CROSS_SELLING                 => IdentityType::CROSS_SELLING,
        self::CROSS_SELLING_GROUP           => IdentityType::CROSS_SELLING_GROUP,
        self::CROSS_SELLING_ITEM            => IdentityType::CROSS_SELLING_ITEM,
        self::CURRENCY                      => IdentityType::CURRENCY,
        self::CUSTOMER                      => IdentityType::CUSTOMER,
        self::CUSTOMER_GROUP                => IdentityType::CUSTOMER_GROUP,
        self::CUSTOMER_ORDER                => IdentityType::CUSTOMER_ORDER,
        self::CUSTOMER_ORDER_ITEM           => IdentityType::CUSTOMER_ORDER_ITEM,
        self::DELIVERY_NOTE                 => IdentityType::DELIVERY_NOTE,
        self::LANGUAGE                      => IdentityType::LANGUAGE,
        self::MANUFACTURER                  => IdentityType::MANUFACTURER,
        self::MANUFACTURER_IMAGE            => IdentityType::MANUFACTURER_IMAGE,
        self::MEASUREMENT_UNIT              => IdentityType::MEASUREMENT_UNIT,
        self::PAYMENT                       => IdentityType::PAYMENT,
        self::PRODUCT                       => IdentityType::PRODUCT,
        self::PRODUCT_ATTRIBUTE             => IdentityType::PRODUCT_ATTRIBUTE,
        self::PRODUCT_IMAGE                 => IdentityType::PRODUCT_IMAGE,
        self::PRODUCT_TYPE                  => IdentityType::PRODUCT_TYPE,
        self::PRODUCT_VARIATION             => IdentityType::PRODUCT_VARIATION,
        self::PRODUCT_VARIATION_VALUE       => IdentityType::PRODUCT_VARIATION_VALUE,
        self::PRODUCT_VARIATION_VALUE_IMAGE => IdentityType::PRODUCT_VARIATION_VALUE_IMAGE,
        self::PRODUCT_WAREHOUSE_INFO        => IdentityType::PRODUCT_WAREHOUSE_INFO,
        self::PRODUCT_STOCK_LEVEL           => IdentityType::PRODUCT_STOCK_LEVEL,
        self::PRODUCT_TO_CATEGORY           => IdentityType::PRODUCT_TO_CATEGORY,
        self::PRODUCT_PRICE                 => IdentityType::PRODUCT_PRICE,
        self::PRODUCT_SPECIFIC              => IdentityType::PRODUCT_SPECIFIC,
        self::SHIPPING_CLASS                => IdentityType::SHIPPING_CLASS,
        self::SHIPPING_METHOD               => IdentityType::SHIPPING_METHOD,
        self::SPECIFIC                      => IdentityType::SPECIFIC,
        self::SPECIFIC_IMAGE                => IdentityType::SPECIFIC_IMAGE,
        self::SPECIFIC_VALUE                => IdentityType::SPECIFIC_VALUE,
        self::SPECIFIC_VALUE_IMAGE          => IdentityType::SPECIFIC_VALUE_IMAGE,
        self::TAX_CLASS                     => IdentityType::TAX_CLASS,
        self::TAX_RATE                      => IdentityType::TAX_RATE,
        self::UNIT                          => IdentityType::UNIT,
        self::WAREHOUSE                     => IdentityType::WAREHOUSE,
    ];

    /** @var array<string, array<string, int>> */
    protected static array $propertyMappings = [
        self::CATEGORY                             => [
            'id'               => IdentityType::CATEGORY,
            'parentCategoryId' => IdentityType::CATEGORY,
        ],
        self::CATEGORY_ATTRIBUTE                   => [
            'id' => IdentityType::CATEGORY_ATTRIBUTE,
        ],
        self::CONFIG_GROUP                         => [
            'id' => IdentityType::CONFIG_GROUP,
        ],
        self::CONFIG_ITEM                          => [
            'id'        => IdentityType::CONFIG_ITEM,
            'productId' => IdentityType::PRODUCT,
        ],
        self::CROSS_SELLING                        => [
            'id'        => IdentityType::CROSS_SELLING,
            'productId' => IdentityType::PRODUCT,
        ],
        self::CROSS_SELLING_ITEM                   => [
            'id'                  => IdentityType::CROSS_SELLING_ITEM,
            'crossSellingGroupId' => IdentityType::CROSS_SELLING_GROUP,
            'productIds'          => IdentityType::PRODUCT,// List of Product identities
        ],
        self::CROSS_SELLING_GROUP                  => [
            'id' => IdentityType::CROSS_SELLING_GROUP,
        ],
        self::CURRENCY                             => [
            'id' => IdentityType::CURRENCY,
        ],
        self::CUSTOMER                             => [
            'id'              => IdentityType::CUSTOMER,
            'customerGroupId' => IdentityType::CUSTOMER_GROUP,
        ],
        self::CUSTOMER_GROUP                       => [
            'id' => IdentityType::CUSTOMER_GROUP,
        ],
        self::CUSTOMER_ORDER                       => [
            'id'               => IdentityType::CUSTOMER_ORDER,
            'customerId'       => IdentityType::CUSTOMER,
            'shippingMethodId' => IdentityType::SHIPPING_METHOD,
        ],
        self::CUSTOMER_ORDER_ITEM                  => [
            'productId' => IdentityType::PRODUCT,
        ],
        self::DELIVERY_NOTE                        => [
            'id'              => IdentityType::DELIVERY_NOTE,
            'customerOrderId' => IdentityType::CUSTOMER_ORDER,
        ],
        self::DELIVERY_NOTE_ITEM                   => [
            'productId' => IdentityType::PRODUCT,
        ],
        self::FILE_UPLOAD                          => [
            'productId' => IdentityType::PRODUCT,
        ],
        self::PRODUCT_IMAGE                        => [
            'id'         => IdentityType::PRODUCT_IMAGE,
            'foreignKey' => IdentityType::PRODUCT,
        ],
        self::CATEGORY_IMAGE                       => [
            'id'         => IdentityType::CATEGORY_IMAGE,
            'foreignKey' => IdentityType::CATEGORY,
        ],
        self::PRODUCT_VARIATION_VALUE_IMAGE        => [
            'id'         => IdentityType::PRODUCT_VARIATION_VALUE_IMAGE,
            'foreignKey' => IdentityType::PRODUCT_VARIATION_VALUE,
        ],
        self::SPECIFIC_IMAGE                       => [
            'id'         => IdentityType::SPECIFIC_IMAGE,
            'foreignKey' => IdentityType::SPECIFIC,
        ],
        self::SPECIFIC_VALUE_IMAGE                 => [
            'id'         => IdentityType::SPECIFIC_VALUE_IMAGE,
            'foreignKey' => IdentityType::SPECIFIC_VALUE,
        ],
        self::MANUFACTURER_IMAGE                   => [
            'id'         => IdentityType::MANUFACTURER_IMAGE,
            'foreignKey' => IdentityType::MANUFACTURER,
        ],
        self::CONFIG_GROUP_IMAGE                   => [
            'id'         => IdentityType::CONFIG_GROUP_IMAGE,
            'foreignKey' => IdentityType::CONFIG_GROUP,
        ],
        self::LANGUAGE                             => [
            'id' => IdentityType::LANGUAGE,
        ],
        self::MANUFACTURER                         => [
            'id' => IdentityType::MANUFACTURER,
        ],
        self::MEASUREMENT_UNIT                     => [
            'id' => IdentityType::MEASUREMENT_UNIT,
        ],
        self::PAYMENT                              => [
            'id'              => IdentityType::PAYMENT,
            'customerOrderId' => IdentityType::CUSTOMER_ORDER,
        ],
        self::PRODUCT                              => [
            'id'                => IdentityType::PRODUCT,
            'masterProductId'   => IdentityType::PRODUCT,
            'manufacturerId'    => IdentityType::MANUFACTURER,
            'measurementUnitId' => IdentityType::MEASUREMENT_UNIT,
            'productTypeId'     => IdentityType::PRODUCT_TYPE,
            'shippingClassId'   => IdentityType::SHIPPING_CLASS,
            'taxClassId'        => IdentityType::TAX_CLASS,
            'unitId'            => IdentityType::UNIT,
        ],
        self::PRODUCT_ATTRIBUTE                    => [
            'id' => IdentityType::PRODUCT_ATTRIBUTE,
        ],
        self::PRODUCT_PRICE                        => [
            'customerId'      => IdentityType::CUSTOMER,
            'productId'       => IdentityType::PRODUCT,
            'customerGroupId' => IdentityType::CUSTOMER_GROUP,
        ],
        self::PRODUCT_STOCK_LEVEL                  => [
            'productId' => IdentityType::PRODUCT,
        ],
        self::PRODUCT_TO_CATEGORY                  => [
            'categoryId' => IdentityType::CATEGORY,
        ],
        self::PRODUCT_CONFIG_GROUP                 => [
            'configGroupId' => IdentityType::CONFIG_GROUP,
        ],
        self::PRODUCT_SPECIFIC                     => [
            'id'              => IdentityType::SPECIFIC,
            'specificValueId' => IdentityType::SPECIFIC_VALUE,
        ],
        self::PRODUCT_TYPE                         => [
            'id' => IdentityType::PRODUCT_TYPE,
        ],
        self::PRODUCT_VARIATION                    => [
            'id' => IdentityType::PRODUCT_VARIATION,
        ],
        self::PRODUCT_VARIATION_VALUE              => [
            'id' => IdentityType::PRODUCT_VARIATION_VALUE,
        ],
        self::PRODUCT_VARIATION_VALUE_EXTRA_CHARGE => [
            'customerGroupId' => IdentityType::CUSTOMER_GROUP,
        ],
        self::PRODUCT_VARIATION_VALUE_INVISIBILITY => [
            'customerGroupId' => IdentityType::CUSTOMER_GROUP,
        ],
        self::PRODUCT_WAREHOUSE_INFO               => [
            'warehouseId' => IdentityType::WAREHOUSE,
        ],
        self::SHIPPING_CLASS                       => [
            'id' => IdentityType::SHIPPING_CLASS,
        ],
        self::SHIPPING_METHOD                      => [
            'id' => IdentityType::SHIPPING_METHOD,
        ],
        self::SHIPMENT                             => [
            'deliveryNoteId' => IdentityType::DELIVERY_NOTE,
        ],
        self::SPECIFIC                             => [
            'id' => IdentityType::SPECIFIC,
        ],
        self::SPECIFIC_VALUE                       => [
            'id' => IdentityType::SPECIFIC_VALUE,
        ],
        self::STATUS_CHANGE                        => [
            'customerOrderId' => IdentityType::CUSTOMER_ORDER,
        ],
        self::TAX_RATE                             => [
            'id' => IdentityType::TAX_RATE,
        ],
        self::UNIT                                 => [
            'id' => IdentityType::UNIT,
        ],
        self::WAREHOUSE                            => [
            'id' => IdentityType::WAREHOUSE,
        ],
    ];

    /**
     * @param string $modelName
     *
     * @return int
     * @throws DefinitionException
     */
    public static function getIdentityType(string $modelName): int
    {
        if (!self::isModel($modelName)) {
            throw DefinitionException::unknownModel($modelName);
        }

        if (!self::hasIdentityType($modelName)) {
            throw DefinitionException::modelMappingNotExists($modelName);
        }
        return self::$mappings[$modelName];
    }

    /**
     * @param string $modelName
     *
     * @return bool
     */
    public static function isModel(string $modelName): bool
    {
        return \in_array($modelName, self::getModels(), true);
    }

    /**
     * @return string[]
     */
    public static function getModels(): array
    {
        if (\is_null(self::$models)) {
            /** @var array<string, string> $models */
            $models       = \array_filter(
                (new \ReflectionClass(self::class))->getConstants(),
                static function ($constantValue, $constantName) {
                    return $constantValue !== self::MODEL_NAMESPACE;
                },
                \ARRAY_FILTER_USE_BOTH
            );
            self::$models = $models;
        }

        return self::$models;
    }

    /**
     * @param string $modelName
     *
     * @return bool
     */
    public static function hasIdentityType(string $modelName): bool
    {
        return isset(self::$mappings[$modelName]);
    }

    /**
     * @param int $type
     *
     * @return string
     * @throws DefinitionException
     */
    public static function getModelByType(int $type): string
    {
        if (!IdentityType::isType($type)) {
            throw DefinitionException::unknownIdentityType($type);
        }

        if (($foundType = \array_search($type, self::$mappings, true)) === false) {
            throw DefinitionException::identityTypeMappingNotExists(0);
        }

        return $foundType;
    }

    /**
     * @param string $modelName
     * @param string $propertyName
     *
     * @return int
     * @throws DefinitionException
     */
    public static function getPropertyIdentityType(string $modelName, string $propertyName): int
    {
        if (!self::isModel($modelName)) {
            throw DefinitionException::unknownModel($modelName);
        }

        if (!self::isIdentityProperty($modelName, $propertyName)) {
            throw DefinitionException::unknownIdentityProperty($modelName, $propertyName);
        }
        return self::$propertyMappings[$modelName][$propertyName];
    }

    /**
     * @param string $modelName
     * @param string $propertyName
     *
     * @return bool
     */
    public static function isIdentityProperty(string $modelName, string $propertyName): bool
    {
        return isset(self::$propertyMappings[$modelName][$propertyName]);
    }

    /**
     * @param string $modelName
     *
     * @return string
     * @throws DefinitionException
     */
    public static function getRelationType(string $modelName): string
    {
        if (!self::isModel($modelName)) {
            throw DefinitionException::unknownModel($modelName);
        }

        return \lcfirst($modelName);
    }
}

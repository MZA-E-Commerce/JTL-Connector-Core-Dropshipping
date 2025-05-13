<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

class IdentityType
{
    public const
        CATEGORY                      = 1,
        CUSTOMER                      = 2,
        CUSTOMER_ORDER                = 4,
        DELIVERY_NOTE                 = 8,
        MANUFACTURER                  = 32,
        PRODUCT                       = 64,
        SPECIFIC                      = 128,
        SPECIFIC_VALUE                = 256,
        PAYMENT                       = 512,
        CROSS_SELLING                 = 1024,
        CROSS_SELLING_GROUP           = 2048,
        CROSS_SELLING_ITEM            = 72,
        SHIPPING_CLASS                = 4096,
        CONFIG_GROUP                  = 6,
        CONFIG_ITEM                   = 10,
        CURRENCY                      = 12,
        CUSTOMER_GROUP                = 14,
        LANGUAGE                      = 18,
        UNIT                          = 20,
        MEASUREMENT_UNIT              = 22,
        PRODUCT_TYPE                  = 24,
        SHIPPING_METHOD               = 26,
        TAX_CLASS                     = 70,
        TAX_RATE                      = 28,
        WAREHOUSE                     = 30,
        CATEGORY_ATTRIBUTE            = 34,
        PRODUCT_ATTRIBUTE             = 36,
        PRODUCT_VARIATION             = 38,
        PRODUCT_VARIATION_VALUE       = 40,
        PRODUCT_IMAGE                 = 42,
        CATEGORY_IMAGE                = 44,
        MANUFACTURER_IMAGE            = 46,
        PRODUCT_VARIATION_VALUE_IMAGE = 48,
        SPECIFIC_IMAGE                = 50,
        SPECIFIC_VALUE_IMAGE          = 52,
        CONFIG_GROUP_IMAGE            = 54,
        PRODUCT_WAREHOUSE_INFO        = 56,
        PRODUCT_STOCK_LEVEL           = 58,
        PRODUCT_TO_CATEGORY           = 60,
        PRODUCT_PRICE                 = 62,
        PRODUCT_SPECIFIC              = 66,
        CUSTOMER_ORDER_ITEM           = 68;

    /** @var array<string, int>|null */
    protected static ?array $types = null;

    /**
     * @param int $type
     *
     * @return bool
     */
    public static function isType(int $type): bool
    {
        return \in_array($type, self::getTypes(), true);
    }

    /**
     * @return array<string, int>
     */
    public static function getTypes(): array
    {
        if (\is_null(self::$types)) {
            /** @var array<string, int> $types */
            $types       = (new \ReflectionClass(self::class))->getConstants();
            self::$types = $types;
        }

        return self::$types;
    }
}

<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Config\CoreConfigInterface;
use Jtl\Connector\Core\Model\Product;
use Psr\Log\LoggerInterface;

class ProductController extends AbstractController
{
    public function __construct(CoreConfigInterface $config, LoggerInterface $logger)
    {
        parent::__construct($config, $logger);
    }

    protected function updateModel(Product $model): void
    {
        $this->updateProductEndpoint($model);
    }
}

/**
 * Example JSON of product data:
[
{
  "entityType": "Product",
  "categories":
  [
        {
             "id":
             [
                  "",
                  4955
             ],
             "productId":
             [
                  "",
                  4955
             ],
             "categoryId":
             [
                  "",
                  707
             ]
        }
  ],
  "configGroups":
  [],
  "fileDownloads":
  [],
  "i18ns":
  [
        {
             "measurementUnitName": null,
             "unitName": "Stk",
             "deliveryStatus": null,
             "productId":
             [
                  "",
                  4955
             ],
             "languageISO": "ger",
             "name": "ich binb der Name in PIMCORE",
             "description": "",
             "shortDescription": "lololo",
             "urlPath": "",
             "metaDescription": "",
             "titleTag": "",
             "metaKeywords": ""
        }
  ],
  "invisibilities":
  [],
  "mediaFiles":
  [],
  "prices":
  [
        {
             "customerId":
             [
                  "",
                  0
             ],
             "items":
             [
                  {
                        "productPriceId":
                        [
                             "",
                             0
                        ],
                        "quantity": 0,
                        "netPrice": 12
                  }
             ],
             "customerGroupId":
             [
                  "c2c6154f05b342d4b2da85e51ec805c9",
                  1
             ],
             "sku": "83152-A-L",
             "vat": 19,
             "id":
             [
                  "",
                  0
             ],
             "productId":
             [
                  "",
                  4955
             ]
        },
        {
             "customerId":
             [
                  "",
                  0
             ],
             "items":
             [
                  {
                        "productPriceId":
                        [
                             "",
                             0
                        ],
                        "quantity": 0,
                        "netPrice": 12
                  }
             ],
             "customerGroupId":
             [
                  "b1d7b4cbe4d846f0b323a9d840800177",
                  2
             ],
             "sku": "83152-A-L",
             "vat": 19,
             "id":
             [
                  "",
                  0
             ],
             "productId":
             [
                  "",
                  4955
             ]
        },
        {
             "customerId":
             [
                  "",
                  0
             ],
             "items":
             [
                  {
                        "productPriceId":
                        [
                             "",
                             0
                        ],
                        "quantity": 0,
                        "netPrice": 12
                  }
             ],
             "customerGroupId":
             [
                  "",
                  0
             ],
             "sku": "83152-A-L",
             "vat": 19,
             "id":
             [
                  "",
                  0
             ],
             "productId":
             [
                  "",
                  4955
             ]
        }
  ],
  "partsLists":
  [],
  "attributes":
  [],
  "specialPrices":
  [],
  "specifics":
  [],
  "warehouseInfo":
  [
        {
             "inflowQuantity": 0,
             "productId":
             [
                  "",
                  4955
             ],
             "warehouseId":
             [
                  "",
                  1
             ],
             "stockLevel": 86
        }
  ],
  "variations":
  [],
  "checksums":
  [],
  "varCombinations":
  [],
  "customerGroupPackagingQuantities":
  [],
  "taxRates":
  [
        {
             "id":
             [
                  "",
                  1
             ],
             "rate": 19,
             "countryIso": "DE"
        }
  ],
  "stockLevel": 86,
  "supplierStockLevel": 0,
  "vat": 19,
  "basePriceFactor": 0,
  "supplierDeliveryTime": 0,
  "measurementUnitCode": "",
  "basePriceUnitCode": "",
  "basePriceUnitName": "",
  "minBestBeforeDate": null,
  "manufacturer": null,
  "id":
  [
        "",
        4955
  ],
  "sku": "83152-A-L",
  "recommendedRetailPrice": 17.647058823529413,
  "note": "",
  "isActive": true,
  "minimumOrderQuantity": 0,
  "ean": "4056144157243",
  "isTopProduct": false,
  "shippingWeight": 0,
  "isNewProduct": false,
  "isSerialNumber": false,
  "isDivisible": false,
  "considerStock": true,
  "permitNegativeStock": false,
  "minimumQuantity": 0,
  "purchasePrice": 0,
  "considerVariationStock": false,
  "modified": "2025-04-22T12:32:33Z",
  "considerBasePrice": false,
  "basePriceDivisor": 0,
  "keywords": "",
  "taric": "",
  "originCountry": "",
  "taxClassId":
  [
        "",
        1
  ],
  "creationDate": "2025-04-10T13:40:07Z",
  "availableFrom": null,
  "sort": 0,
  "shippingClassId":
  [
        "",
        1
  ],
  "productWeight": 0,
  "manufacturerNumber": "",
  "serialNumber": "",
  "isbn": "",
  "unNumber": "",
  "hazardIdNumber": "",
  "asin": "",
  "masterProductId":
  [
        "",
        0
  ],
  "isMasterProduct": false,
  "packagingQuantity": 0,
  "partsListId":
  [
        "",
        0
  ],
  "upc": "",
  "productTypeId":
  [
        "",
        0
  ],
  "epid": "",
  "isBestBefore": false,
  "isBatch": false,
  "manufacturerId":
  [
        "",
        0
  ],
  "measurementUnitId":
  [
        "",
        0
  ],
  "measurementQuantity": 0,
  "basePriceUnitId":
  [
        "",
        0
  ],
  "basePriceQuantity": 0,
  "width": 0,
  "height": 0,
  "length": 0,
  "unitId":
  [
        "",
        1
  ],
  "nextAvailableInflowDate": null,
  "additionalHandlingTime": 0,
  "nextAvailableInflowQuantity": 0,
  "newReleaseDate": null,
  "discountable": true
}
]
*/
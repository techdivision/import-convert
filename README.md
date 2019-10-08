# M2IF - Magento 2 Import Converter

```json
{
  ...
  "params" : [
    {
      "custom-validations" : {
        "sku" : [ ".+" ],
        "tier-price-value-type" : [ "Fixed" ],
        "tier-price-website" : [ "All Websites" ],
        "tier-price-customer-group" : [ "ALL GROUPS" ]
      }
    }
  ]
  ...
  "operations" : [
    {
      "name" : "convert",
      "plugins" : [
        {
          "id": "import.plugin.global.data"
        },
        {
          "id": "import.plugin.subject",
          "subjects" : [
            {
              "id": "import_converter.subject.converter",
              "identifier": "files",
              "create-imported-file": false,
              "file-resolver": {
                "prefix": "product-import-tier-price"
              },
              "observers": [
                {
                  "import": [
                    "import_converter.observer.validation.generic",
                    "macs_import.observer.export.price"
                  ]
                }
              ],
              "callbacks": [
                {
                  "sku": [
                    "import_converter.callback.generic.configuration.based.regex.validator"
                  ],
                  "tier_price_value_type": [
                    "import_converter.callback.generic.configuration.based.validator"
                  ],
                  "tier_price_customer_group": [
                    "import_converter.callback.generic.configuration.based.validator"
                  ],
                  "tier_price_website": [
                    "import_converter.callback.generic.configuration.based.validator"
                  ]
                }
              ]
            }
          ]
        }
      ]
    }
  ]
}
```

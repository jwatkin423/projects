<?php

// Path to the commerce API providers
$client_path = "\\Adrenalads\\CommerceApi\\Provider";

return [

    "api_clients" => [
        "localhost" => [
            "skin" => "hyfind",
            "cache" => false,
            "client" => "{$client_path}\\Dummy",
            "options" => []
        ],
        // hyfind.de
        "cnx_de" => [
            "skin" => "hyfind",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "options" => [
                "apiKey" => "a02e8eebdd17062d387fb459ed015529",
                "trackingId" => "613971"
            ]
        ],
        // hyfind.fr
        "cnx_fr" => [
            "skin" => "hyfind",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "options" => [
                "apiKey" => "fbc740bbebad08e83ffefb880217e4a3",
                "trackingId" => "616725"
            ]
        ],
        // hyfind.co.uk
        "cnx_gb" => [
            "skin" => "hyfind",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "options" => [
                "apiKey" => "e3ef37d6bfecb9328539c64ab51369ca",
                "trackingId" => "616726"
            ]
        ],
        // ealeo.com
        "cnx_us" => [
            "skin" => "ealeo",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "options" => [
                "api_key" => "a7561e1b6af62381bea46dce864d896e",
                "publisher_id" => "621972",
                "featuredTerms" => ['electronics', 'office', 'footwear', 'kitchen']
            ]
        ],
        // new swoopdeal
        "cnx_us_2" => [
            "skin" => "ealeo",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "api_url" => 'http://catalog.bizrate.com/services/catalog/v1/api',
            "endpoint" => [
                "product" => 'product/1',
                "taxonomy" => 'taxonomy/1'
            ],
            "options" => [
                "api_key" => "7afec9dd9891e3913606ccfee5e32eaf",
                "publisher_id" => "611445",
                "featuredTerms" => ['electronics', 'office', 'footwear', 'kitchen']
            ]
        ],
        // new swoopdeal
        "cnxpla_de" => [
            "skin" => "ealeo",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "api_url" => 'http://catalog.shopzilla.de/services/catalog/v1/api',
            "endpoint" => [
                "product" => 'product/5',
                "taxonomy" => 'taxonomy/5'
            ],
            "options" => [
                "api_key" => "e5f7c138078e454092962b65f2e05353",
                "publisher_id" => "676166",
                "featuredTerms" => ['electronics', 'office', 'footwear', 'kitchen']
            ]
        ],
        // new swoopdeal
        "cnxpla_fr" => [
            "skin" => "ealeo",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "api_url" => 'http://catalog.shopzilla.fr/services/catalog/v1/api',
            "endpoint" => [
                "product" => 'product/4',
                "taxonomy" => 'taxonomy/4'
            ],
            "options" => [
                "api_key" => "2c82ff2523923c912dc45afddd9b4e25",
                "publisher_id" => "676167",
                "featuredTerms" => ['electronics', 'office', 'footwear', 'kitchen']
            ]
        ],
        // new swoopdeal
        "cnx_gb_2" => [
            "skin" => "ealeo",
            "client" => "{$client_path}\\Connexity",
            "cache" => false,
            "api_url" => 'http://catalog.bizrate.co.uk/services/catalog/v1/api',
            "endpoint" => [
                "product" => 'product/3',
                "taxonomy" => 'taxonomy/3'
            ],
            "options" => [
                "api_key" => "e3ef37d6bfecb9328539c64ab51369ca",
                "publisher_id" => "616726",
                "featuredTerms" => ['electronics', 'office', 'footwear', 'kitchen']
            ]
        ],
    ],

    "default_host" => env('ADV_KEY', 'cnx_us')

];
<?php
return [
    [
    "code" => "plain_price",
    "data" => [
        "name" => getCoreConfig("ecommerce/plain_price/titulo"),
        "enabled" => getCoreConfig("ecommerce/plain_price/enabled"),
        "time" => getCoreConfig("ecommerce/plain_price/dias"),
        "price" => getShippingPlainPrice(),
        "iva" => getCoreConfig("ecommerce/plain_price/iva"),
        "html" => "<p>* Tiempo expresado en dias habiles</p>"
        ]
    ]

];
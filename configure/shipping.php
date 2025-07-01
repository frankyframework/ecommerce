<?php
return [
    [
    "code" => "plain_price",
    "data" => [
        "name" => getCoreConfig("ecommerce/plain-price/titulo"),
        "enabled" => getCoreConfig("ecommerce/plain-price/enabled"),
        "time" => getCoreConfig("ecommerce/plain-price/dias"),
        "price" => getShippingPlainPrice(),
        "iva" => getCoreConfig("ecommerce/plain-price/iva"),
        "html" => "<p>* Tiempo expresado en dias habiles</p>"
        ]
    ]

];
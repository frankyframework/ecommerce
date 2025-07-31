<?php
return [
    [
    "code" => "free_pay",
    "data" => [
        "name" =>  getCoreConfig("ecommerce/free_pay/name"),
        "enabled" => getFreePayEnabled(),
        "html" =>  "<a class=\"_btn _primary_checkout\" href=\"javascript:placeOrder()\">Terminar compra</a>",
        "execute" => "placeOrderFreePay",
        "callback" => getCallbakFreePay(),
        "email_template" =>  getCoreConfig("ecommerce/free_pay/email-order"),
        ]
    ]
];
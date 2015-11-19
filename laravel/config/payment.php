<?php

return [

    "services" => [
        "paypal" => \App\PaymentPaypal::class,
    ],

    "currency" => "BRL",

    "globalTransactionDescription" => "Descrição da Transação",
];

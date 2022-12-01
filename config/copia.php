<?php

return [
    'payment' => \ReinVanOyen\Copia\Payment\NullPayment::class,
    'orderCreator' => \ReinVanOyen\Copia\Order\DefaultOrderCreator::class,

    'fulfilments' => [
        'shipping' => \ReinVanOyen\Copia\Fulfilment\Shipping::class,
    ],
];

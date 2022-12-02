<?php

return [
    'payment' => \ReinVanOyen\Copia\Payment\NullPayment::class,
    'order' => [
        'creator' => \ReinVanOyen\Copia\Order\DefaultOrderCreator::class,
        'id_generator' => \ReinVanOyen\Copia\Order\DefaultOrderIdGenerator::class,
     ],
    'cart' => [
        'storage' => \ReinVanOyen\Copia\Cart\SessionCartStorage::class,
    ],
    'fulfilment' => [
        'default' => 'shipping',
        'methods' => [
            'shipping' => \ReinVanOyen\Copia\Fulfilment\Shipping::class,
            'pickup' => \ReinVanOyen\Copia\Fulfilment\PickUp::class,
        ],
    ]
];

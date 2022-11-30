<?php

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Fulfilment;

class Shipping implements Fulfilment
{
    public function getId()
    {
        return 'shipping';
    }

    public function getCost(CartManager $cart): float
    {
        return 7;
    }

    public function getTitle(): string
    {
        return 'Shipping';
    }
}

<?php

namespace ReinVanOyen\Copia\Contracts;

use ReinVanOyen\Copia\Cart\CartManager;

interface OrderIdGenerator
{
    public function generate(CartManager $cart): string;
}

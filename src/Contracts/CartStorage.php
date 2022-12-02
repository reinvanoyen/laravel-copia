<?php

namespace ReinVanOyen\Copia\Contracts;

use ReinVanOyen\Copia\Models\Cart;

interface CartStorage
{
    public function store(Cart $cart);
    public function retrieve(): ?Cart;
}

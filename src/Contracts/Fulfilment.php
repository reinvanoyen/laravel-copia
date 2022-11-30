<?php

namespace ReinVanOyen\Copia\Contracts;

use ReinVanOyen\Copia\Cart\CartManager;

interface Fulfilment
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param CartManager $cart
     * @return float
     */
    public function getCost(CartManager $cart): float;

    /**
     * @return string
     */
    public function getTitle(): string;
}

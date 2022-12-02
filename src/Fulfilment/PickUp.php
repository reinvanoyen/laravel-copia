<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Fulfilment;

class PickUp implements Fulfilment
{
    public function getId()
    {
        return 'pickup';
    }

    public function getCost(CartManager $cart): float
    {
        return 0;
    }

    public function getTitle(): string
    {
        return 'Pickup';
    }
}

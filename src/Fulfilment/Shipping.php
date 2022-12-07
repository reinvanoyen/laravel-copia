<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Fulfilment;
use ReinVanOyen\Copia\Contracts\Orderable;

class Shipping implements Fulfilment
{
    public function getId()
    {
        return 'shipping';
    }

    public function getCost(CartManager $cart): float
    {
        return (count($cart->items()) >= 3 ? 5 : 10);
    }

    public function getTitle(): string
    {
        return 'Shipping';
    }

    /**
     * @param Orderable $order
     * @return mixed|void
     */
    public function process(Orderable $order)
    {
        // The order was unfulfilled
        $order->setFulfilmentStatus(FulfilmentStatus::UNFULFILLED);
    }
}

<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Fulfilment;
use ReinVanOyen\Copia\Contracts\Orderable;

class PickUp implements Fulfilment
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'pickup';
    }

    /**
     * @param CartManager $cart
     * @return float
     */
    public function getCost(CartManager $cart): float
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Pickup';
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

<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Fulfilment;
use ReinVanOyen\Copia\Contracts\Orderable;

class NullFulfilment implements Fulfilment
{
    public function getId()
    {
        return 'null';
    }

    public function getCost(CartManager $cart): float
    {
        return 0;
    }

    public function getTitle(): string
    {
        return 'Null fulfilment';
    }

    /**
     * @param Orderable $order
     * @return mixed|void
     */
    public function process(Orderable $order)
    {
        // The order was fulfilled
        $order->setFulfilmentStatus(FulfilmentStatus::FULFILLED);
    }
}

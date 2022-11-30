<?php

namespace ReinVanOyen\Copia\Contracts;

use ReinVanOyen\Copia\Contracts\Fulfilment;

interface Orderable
{
    public function add($cartItem);
    public function getItems();
    public function setFulfillment(Fulfilment $fulfillmentMethod);
    public function getFulfillment(): Fulfilment;
    public function getSubTotal(): float;
    public function getTotal(): float;
    public function getReduction(): float;
}

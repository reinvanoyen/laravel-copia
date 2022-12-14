<?php

namespace ReinVanOyen\Copia\Contracts;

use Illuminate\Contracts\Events\Dispatcher;
use ReinVanOyen\Copia\Cart\CartManager;

interface OrderCreator
{
    public function __construct(OrderIdGenerator $generator, Dispatcher $events);
    public function createOrder(CartManager $cart, Customer $customer): Orderable;
}

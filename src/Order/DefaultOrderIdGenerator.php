<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Order;

use Illuminate\Support\Str;
use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\OrderIdGenerator;

class DefaultOrderIdGenerator implements OrderIdGenerator
{
    public function generate(CartManager $cart): string
    {
        return Str::random(12);
    }
}

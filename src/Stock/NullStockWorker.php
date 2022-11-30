<?php

namespace ReinVanOyen\Copia\Stock;

use ReinVanOyen\Copia\Contracts\Buyable;
use ReinVanOyen\Copia\Contracts\StockWorker;

class NullStockWorker implements StockWorker
{
    public function isAvailable(Buyable $buyable, float $quantity = 1): bool
    {
        return true;
    }

    public function increment(Buyable $buyable, float $quantity = 1)
    {
        // Do nothing
    }

    public function decrement(Buyable $buyable, float $quantity = 1)
    {
        // Do nothing
    }

    public function getQuantity(Buyable $buyable): int
    {
        return 0;
    }
}

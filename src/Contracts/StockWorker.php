<?php

namespace ReinVanOyen\Copia\Contracts;

interface StockWorker
{
    public function isAvailable(Buyable $buyable, float $quantity = 1): bool;
    public function increment(Buyable $buyable, float $quantity = 1);
    public function decrement(Buyable $buyable, float $quantity = 1);
    public function getQuantity(Buyable $buyable): int;
}

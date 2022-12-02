<?php

namespace ReinVanOyen\Copia\Contracts;

interface StockWorker
{
    public function isAvailable(Buyable $buyable, int $quantity = 1): bool;
    public function increment(Buyable $buyable, int $quantity = 1);
    public function decrement(Buyable $buyable, int $quantity = 1);
    public function setQuantity(Buyable $buyable, int $quantity);
    public function getQuantity(Buyable $buyable): int;
}

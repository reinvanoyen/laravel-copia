<?php

namespace ReinVanOyen\Copia\Contracts;

interface TaxRate
{
    /**
     * @param float $amount
     * @return float
     */
    public function getTax(float $amount): float;
}

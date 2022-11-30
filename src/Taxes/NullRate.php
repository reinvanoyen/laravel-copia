<?php

namespace ReinVanOyen\Copia\Taxes;

use ReinVanOyen\Copia\Contracts\TaxRate;

class NullRate implements TaxRate
{
    public function getTax(float $amount): float
    {
        return 0;
    }
}

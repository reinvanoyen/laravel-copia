<?php

namespace ReinVanOyen\Copia\Fulfilment;

use ReinVanOyen\Copia\Contracts\Fulfilment;

class FulfilmentManager
{
    public function get(string $name): ?Fulfilment
    {
        $fulfilments = config('copia.fulfilment.methods');

        if ( !isset($fulfilments[$name])) {
            return null;
        }

        return app($fulfilments[$name]);
    }
}

<?php

namespace ReinVanOyen\Copia\Contracts;

interface Payment
{
    /**
     * @param Orderable $orderable
     * @return mixed
     */
    public function pay(Orderable $orderable);
}

<?php

namespace ReinVanOyen\Copia\Contracts;

interface Buyable
{
    /**
     * @return string
     */
    public function getBuyableId(): string;

    /**
     * @return string
     */
    public function getBuyableTitle(): string;

    /**
     * @return string
     */
    public function getBuyableDescription(): string;

    /**
     * @return float
     */
    public function getBuyablePrice(): float;

    /**
     * @return float
     */
    public function getBuyableWeight(): float;

    /**
     * @return StockWorker
     */
    public function getBuyableStockWorker(): StockWorker;

    /**
     * @return TaxRate
     */
    public function getBuyableTaxRate(): TaxRate;
}

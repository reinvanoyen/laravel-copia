<?php

namespace ReinVanOyen\Copia\Stock;

use Illuminate\Database\Eloquent\Builder;
use ReinVanOyen\Copia\Contracts\Buyable;
use ReinVanOyen\Copia\Contracts\StockWorker;
use ReinVanOyen\Copia\Models\Stock;
use ReinVanOyen\Copia\Models\StockItem;

class DatabaseStockWorker implements StockWorker
{
    /**
     * @var string $hid
     */
    private $hid;

    /**
     * @var Stock $stock
     */
    private $stock;

    /**
     * @param string $hid
     */
    public function __construct(string $hid)
    {
        $this->hid = $hid;
        $this->stock = $this->getStock();
    }

    public function isAvailable(Buyable $buyable, float $quantity = 1): bool
    {
        return ($this->getQuantity($buyable) >= $quantity);
    }

    public function increment(Buyable $buyable, float $quantity = 1)
    {
        $item = $this->getStockItem($buyable);

        if ($item) {
            $item->quantity = $item->quantity + $quantity;
            $item->save();
        }
    }

    public function decrement(Buyable $buyable, float $quantity = 1)
    {
        $item = $this->getStockItem($buyable);

        if ($item) {
            $item->quantity = $item->quantity - $quantity;
            $item->save();
        }
    }

    public function getQuantity(Buyable $buyable): int
    {
        $item = $this->getStockItem($buyable);

        if (! $item) {
            return 0;
        }

        return $item->quantity;
    }

    /**
     * @param Buyable $buyable
     * @return StockItem|null
     */
    private function getStockItem(Buyable $buyable): ?StockItem
    {
        return $this->stock->stockItems()
            ->whereHasMorph('stockable', [get_class($buyable)], function (Builder $query) use ($buyable) {
                $query->where('id', $buyable->id);
            })
            ->first();
    }

    /**
     * @return Stock
     */
    private function getStock(): Stock
    {
        return Stock::where('hid', $this->hid)->first();
    }
}

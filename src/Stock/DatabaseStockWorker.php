<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Stock;

use Illuminate\Contracts\Events\Dispatcher;
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
    private string $hid;

    /**
     * @var Stock $stock
     */
    private Stock $stock;

    /**
     * @var Dispatcher $events
     */
    private Dispatcher $events;

    /**
     * @param string $stock
     * @param Dispatcher $events
     */
    public function __construct(string $stock, Dispatcher $events)
    {
        $this->hid = $stock;
        $this->stock = $this->getStock();
        $this->events = $events;
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return bool
     */
    public function isAvailable(Buyable $buyable, int $quantity = 1): bool
    {
        return ($this->getQuantity($buyable) >= $quantity);
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return void
     */
    public function increment(Buyable $buyable, int $quantity = 1)
    {
        $item = $this->getStockItem($buyable);

        if ($item) {
            $item->quantity = $item->quantity + $quantity;
            $item->save();
            $this->events->dispatch('copia.stock.quantity', $item);
        }
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return void
     */
    public function decrement(Buyable $buyable, int $quantity = 1)
    {
        $item = $this->getStockItem($buyable);

        if ($item) {
            $item->quantity = $item->quantity - $quantity;
            $item->save();
            $this->events->dispatch('copia.stock.quantity', $item);
        }
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return void
     */
    public function setQuantity(Buyable $buyable, int $quantity)
    {
        $item = $this->getStockItem($buyable);

        if ($item) {
            $item->quantity = $quantity;
            $item->save();
            $this->events->dispatch('copia.stock.quantity', $item);
        }
    }

    /**
     * @param Buyable $buyable
     * @return int
     */
    public function getQuantity(Buyable $buyable): int
    {
        $item = $this->getStockItem($buyable);

        return ($item ? $item->quantity : 0);
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

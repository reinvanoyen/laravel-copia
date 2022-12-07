<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReinVanOyen\Copia\Contracts\Orderable;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->stockItems as $stockItem) {
            $stockItem->delete();
        }

        return parent::delete();
    }
}

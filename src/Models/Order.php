<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReinVanOyen\Copia\Contracts\Orderable;

class Order extends Model implements Orderable
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->orderItems as $orderItem) {
            $orderItem->delete();
        }

        return parent::delete();
    }
}

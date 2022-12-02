<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->cartItems as $cartItem) {
            $cartItem->delete();
        }

        return parent::delete();
    }
}

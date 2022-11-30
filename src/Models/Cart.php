<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function delete()
    {
        foreach ($this->cartItems as $cartItem) {
            $cartItem->delete();
        }

        return parent::delete();
    }
}

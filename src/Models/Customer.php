<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \ReinVanOyen\Copia\Contracts\Customer as CustomerInterface;

class Customer extends Model implements CustomerInterface
{
    use HasFactory;

    protected $table = 'customers';
}

<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \ReinVanOyen\Copia\Contracts\Customer as CustomerInterface;

class Customer extends Model implements CustomerInterface
{
    use HasFactory;

    protected $table = 'customers';

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address_street ?? '';
    }

    /**
     * @return string
     */
    public function getHouseNumber(): string
    {
        return $this->address_number ?? '';
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->address_postal_code ?? '';
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->address_city ?? '';
    }

    /**
     * @return string
     */
    public function getCountryISO(): string
    {
        return 'BE';
    }

    /**
     * @return string
     */
    public function getTelephoneNumber(): string
    {
        return $this->telephone ?? '';
    }
}

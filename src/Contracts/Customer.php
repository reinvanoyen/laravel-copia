<?php

namespace ReinVanOyen\Copia\Contracts;

interface Customer
{
    public function getEmail(): string;
    public function getFullName(): string;
    public function getAddress(): string;
    public function getHouseNumber(): string;
    public function getPostalCode(): string;
    public function getCity(): string;
    public function getCountryISO(): string;
    public function getTelephoneNumber(): string;
}

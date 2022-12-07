<?php

namespace ReinVanOyen\Copia\Contracts;

interface Orderable
{
    public function getOrderId(): string;
    public function getCustomer(): Customer;

    public function setPaymentId(string $id);
    public function setPaymentStatus(int $paymentStatus);

    public function getTotal(): float;

    public function getFulfilment(): ?Fulfilment;
    public function setFulfilmentStatus(int $fulfilmentStatus);

    public function getWeight(): float;
    public function getItems();

    /*
    public function add($cartItem);
    public function getItems();
    public function setFulfillment(Fulfilment $fulfillmentMethod);
    public function getFulfillment(): Fulfilment;
    public function getSubTotal(): float;
    public function getReduction(): float;*/
}

<?php

namespace ReinVanOyen\Copia\Order;

use Illuminate\Support\Str;
use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Customer;
use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Contracts\OrderCreator;
use ReinVanOyen\Copia\Models\Order;
use Illuminate\Contracts\Events\Dispatcher;

class DefaultOrderCreator implements OrderCreator
{
    /**
     * @var Dispatcher $events
     */
    private $events;

    /**
     * @param Dispatcher $events
     * @return void
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * @param CartManager $cart
     * @param Customer $customer
     * @return Orderable
     */
    public function createOrder(CartManager $cart, Customer $customer): Orderable
    {
        $order = new Order();

        // Generate an order id
        $order->order_id = Str::random(12);
        $order->status = OrderStatus::OPEN;

        // Copy costs
        $order->total = $cart->getTotal();
        $order->subtotal = $cart->getSubTotal();
        $order->reduction = $cart->getReduction();
        $order->fulfilment_cost = $cart->getFulfilmentCost();

        // Store the fulfilment method
        $order->fulfilment_id = ($cart->getFulfilment() ? $cart->getFulfilment()->getId() : null);

        // Associate the order with the customer
        $order->customer()->associate($customer);
        $order->save();

        foreach ($cart->items() as $item) {
            $originalAttrs = $item->getAttributes();
            $order->orderItems()->create([
                'buyable_id' => $originalAttrs['buyable_id'],
                'buyable_type' => $originalAttrs['buyable_type'],
                'quantity' => $originalAttrs['quantity'],
            ]);
        }

        $this->events->dispatch('copia.order.created', $order);

        return $order;
    }
}

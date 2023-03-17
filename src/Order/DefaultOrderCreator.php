<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Order;

use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Customer;
use ReinVanOyen\Copia\Contracts\OrderIdGenerator;
use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Contracts\OrderCreator;
use ReinVanOyen\Copia\Fulfilment\FulfilmentStatus;
use ReinVanOyen\Copia\Models\Order;
use Illuminate\Contracts\Events\Dispatcher;
use ReinVanOyen\Copia\Payment\PaymentStatus;

class DefaultOrderCreator implements OrderCreator
{
    /**
     * @var OrderIdGenerator $identifier
     */
    private $identifier;

    /**
     * @var Dispatcher $events
     */
    private $events;

    /**
     * @param Dispatcher $events
     * @return void
     */
    public function __construct(OrderIdGenerator $identifier, Dispatcher $events)
    {
        $this->identifier = $identifier;
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
        $order->order_id = $this->identifier->generate($cart);
        $order->status = OrderStatus::OPEN;

        // Copy costs
        $order->total = $cart->getTotal();
        $order->subtotal = $cart->getSubTotal();
        $order->reduction = $cart->getReduction();
        $order->fulfilment_cost = $cart->getFulfilmentCost();

        // Store the fulfilment method
        $order->fulfilment = ($cart->getFulfilment() ? $cart->getFulfilment()->getId() : null);

        // Associate the order with the customer
        $order->customer()->associate($customer);
        $order->save();

        $order->setFulfilmentStatus(FulfilmentStatus::UNFULFILLED);
        $order->setPaymentStatus(PaymentStatus::PENDING);

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

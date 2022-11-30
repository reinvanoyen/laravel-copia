<?php

namespace ReinVanOyen\Copia\Cart;

use Illuminate\Session\SessionManager;
use Illuminate\Support\Str;
use ReinVanOyen\Copia\Contracts\Buyable;
use ReinVanOyen\Copia\Contracts\Customer;
use ReinVanOyen\Copia\Contracts\Fulfilment;
use ReinVanOyen\Copia\Fulfilment\Shipping;
use ReinVanOyen\Copia\Models\Cart;
use ReinVanOyen\Copia\Models\CartItem;
use Illuminate\Contracts\Events\Dispatcher;
use ReinVanOyen\Copia\Models\Order;

class CartManager
{
    /**
     * @var SessionManager $sessions
     */
    private $sessions;

    /**
     * @var Dispatcher $events
     */
    private $events;

    /**
     * @var $cart
     */
    private $cart;

    /**
     * @param SessionManager $sessions
     * @param Dispatcher $dispatcher
     */
    public function __construct(SessionManager $sessions, Dispatcher $events)
    {
        $this->sessions = $sessions;
        $this->events = $events;
        $this->restore();
    }

    /**
     * @return void
     */
    public function restore()
    {
        if ($this->sessions->has('cartId')) {
            $cart = Cart::find($this->sessions->get('cartId'));

            if ($cart) {
                $this->cart = $cart;
                $this->events->dispatch('copia.cart.restored', $this->cart);
                return;
            }
        }

        // Create new cart
        $this->cart = new Cart();
        $this->cart->save();

        // Store the newly create cart id in session
        $this->sessions->put('cartId', $this->cart->id);
        $this->events->dispatch('copia.cart.created', $this->cart);
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return void
     */
    public function add(Buyable $buyable, int $quantity = 1)
    {
        $stock = $buyable->getBuyableStockWorker();

        if (! $stock->isAvailable($buyable, $quantity)) {
            return;
        }

        // Check if the buyable is already in the cart
        $cartItem = $this->getCartItemFromBuyable($buyable);

        if ($cartItem) {
            // Cart item already exists, so just increment the quantity
            $cartItem->quantity = $cartItem->quantity + $quantity;
            $cartItem->save();
            $this->events->dispatch('copia.cart.quantity', $cartItem);
            return;
        }

        // Create a new cart item
        $cartItem = new CartItem();
        $cartItem->cart()->associate($this->cart);
        $cartItem->buyable_id = $buyable->id;
        $cartItem->buyable_type = get_class($buyable);
        $cartItem->quantity = $quantity;
        $cartItem->save();

        $this->events->dispatch('copia.cart.added', $cartItem);
    }

    /**
     * @param Buyable $buyable
     * @param int $quantity
     * @return void
     */
    public function setQuantity(Buyable $buyable, int $quantity)
    {
        $cartItem = $this->getCartItemFromBuyable($buyable);

        if ($cartItem) {
            $cartItem->quantity = $cartItem->quantity + $quantity;
            $cartItem->save();
            $this->events->dispatch('copia.cart.quantity', $cartItem);
        }
    }

    /**
     * @param Buyable $buyable
     * @return void
     */
    public function remove(Buyable $buyable)
    {
        $cartItem = $this->getCartItemFromBuyable($buyable);

        if ($cartItem) {
            $cartItem->delete();
            $this->events->dispatch('copia.cart.removed', $cartItem);
        }
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->cart->delete();
        $this->sessions->forget('cartId');
        $this->events->dispatch('copia.cart.cleared');
    }

    /**
     * @return mixed
     */
    public function items()
    {
        return $this->cart->cartItems;
    }

    /**
     * @param Fulfilment $fulfilment
     * @return void
     */
    public function setFulfilment(Fulfilment $fulfilment)
    {
        $this->cart->fulfilment = $fulfilment->getId();
        $this->cart->save();
        $this->events->dispatch('copia.cart.fulfilment', $fulfilment);
    }

    /**
     * @return void
     */
    public function getFulfilment(): ?Fulfilment
    {
        if ($this->cart->fulfilment === 'shipping') {
            return new Shipping();
        }

        return null;
    }

    /**
     * @return float
     */
    public function getFulfilmentCost(): float
    {
        $fulfilment = $this->getFulfilment();

        return ($fulfilment ? $fulfilment->getCost($this) : 0);
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        $cost = 0;

        foreach ($this->items() as $item) {
            $cost += $item->getPrice();
        }

        return $cost;
    }

    /**
     * @return float
     */
    public function getReduction(): float
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = $this->getSubTotal() + $this->getFulfilmentCost();

        /*
        if (($discount = $this->getDiscount())) {
            $total = $total - $discount->coupon->getReduction($this);
        }*/

        return $total;
    }

    /**
     * @param Customer $customer
     * @return Order
     */
    public function placeOrder(Customer $customer): Order
    {
        $order = new Order();

        // Generate an order id
        $order->order_id = Str::random(12);

        // Copy costs
        $order->total = $this->getTotal();
        $order->subtotal = $this->getSubTotal();
        $order->reduction = $this->getReduction();
        $order->fulfilment_cost = $this->getFulfilmentCost();

        // Store the fulfilment method
        $order->fulfilment_id = ($this->getFulfilment() ? $this->getFulfilment()->getId() : null);

        // Associate the order with the customer
        $order->customer()->associate($customer);
        $order->save();

        foreach ($this->items() as $item) {

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

    /**
     * @param Buyable $buyable
     * @return mixed
     */
    private function getCartItemFromBuyable(Buyable $buyable)
    {
        return CartItem::where([
            'buyable_id' => $buyable->id,
            'buyable_type' => get_class($buyable),
            'cart_id' => $this->cart->id,
        ])->first();
    }
}

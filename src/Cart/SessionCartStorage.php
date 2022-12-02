<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Cart;

use Illuminate\Session\SessionManager;
use ReinVanOyen\Copia\Contracts\CartStorage;
use ReinVanOyen\Copia\Models\Cart;

class SessionCartStorage implements CartStorage
{
    /**
     * @var SessionManager $sessions
     */
    private SessionManager $sessions;

    /**
     * @param SessionManager $sessions
     */
    public function __construct(SessionManager $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * @param Cart $cart
     * @return void
     */
    public function store(Cart $cart)
    {
        $this->sessions->put('cartId', $cart->id);
    }

    /**
     * @return Cart|null
     */
    public function retrieve(): ?Cart
    {
        return ($this->sessions->has('cartId') ? Cart::find($this->sessions->get('cartId')) : null);
    }
}

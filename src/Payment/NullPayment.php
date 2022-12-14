<?php

namespace ReinVanOyen\Copia\Payment;

use Illuminate\Contracts\Events\Dispatcher;
use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Contracts\Payment;

class NullPayment implements Payment
{
    /**
     * @var Dispatcher $dispatcher
     */
    private $dispatcher;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Orderable $order
     * @return mixed|void
     */
    public function pay(Orderable $order)
    {
        // Payment complete
        $order->setPaymentStatus(PaymentStatus::PAID);
        // Dispatch payment complete event
        $this->dispatcher->dispatch('copia.payment.complete', $order);

        return '/';
    }
}

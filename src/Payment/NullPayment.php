<?php

namespace ReinVanOyen\Copia\Payment;

use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Contracts\Payment;

class NullPayment implements Payment
{
    /**
     * @param Orderable $order
     * @return mixed|void
     */
    public function pay(Orderable $order)
    {
        // Payment complete
        $order->setPaymentStatus(PaymentStatus::PAID);

        return '/';
    }
}

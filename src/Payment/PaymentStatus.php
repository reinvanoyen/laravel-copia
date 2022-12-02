<?php
declare(strict_types=1);

namespace ReinVanOyen\Copia\Payment;

class PaymentStatus
{
    const PENDING = 0;
    const PAID = 1;
    const CANCELLED = 2;
    const FAILED = 3;
    const EXPIRED = 4;
    const REFUNDED = 5;
}

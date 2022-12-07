<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Payment\PaymentStatus;

class Order extends Model implements Orderable
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->orderItems as $orderItem) {
            $orderItem->delete();
        }

        return parent::delete();
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setPaymentId(string $id)
    {
        $this->payment_id = $id;
        $this->save();
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @param int $paymentStatus
     * @return void
     */
    public function setPaymentStatus(int $paymentStatus)
    {
        $this->payment_status = $paymentStatus;
        $this->save();

        if ($paymentStatus === PaymentStatus::PAID) {
            Event::dispatch('copia.payment.completed', $this);
        } else {
            Event::dispatch('copia.payment.status.change', $this);
        }
    }
}

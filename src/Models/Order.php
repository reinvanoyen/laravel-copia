<?php

namespace ReinVanOyen\Copia\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use ReinVanOyen\Copia\Contracts\Customer as CustomerInterface;
use ReinVanOyen\Copia\Contracts\Fulfilment;
use ReinVanOyen\Copia\Contracts\Orderable;
use ReinVanOyen\Copia\Fulfilment\FulfilmentManager;
use ReinVanOyen\Copia\Payment\PaymentStatus;

class Order extends Model implements Orderable
{
    use HasFactory;

    protected $table = 'orders';

    protected $casts = [
        'fulfilment_data' => 'array',
    ];

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
     * @return float
     */
    public function getWeight(): float
    {
        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += $item->quantity * $item->buyable->getBuyableWeight();
        }

        return $weight;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->orderItems;
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
     * @return Fulfilment|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getFulfilment(): ?Fulfilment
    {
        return app(FulfilmentManager::class)
            ->get($this->fulfilment);
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @param int $fulfilmentStatus
     * @return void
     */
    public function setFulfilmentStatus(int $fulfilmentStatus)
    {
        $this->fulfilment_status = $fulfilmentStatus;
        $this->save();

        Event::dispatch('copia.fulfilment.status.change', $this);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getFulfilmentData(string $key)
    {
        return $this->fulfilment_data[$key] ?? null;
    }

    /**
     * @param array $data
     * @return void
     */
    public function setFulfilmentData(array $data)
    {
        $originalData = $this->fulfilment_data;

        foreach ($data as $key => $value) {
            $originalData[$key] = $value;
        }

        $this->fulfilment_data = $data;
        $this->save();
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

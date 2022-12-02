<?php

namespace ReinVanOyen\Copia;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\CartStorage;
use ReinVanOyen\Copia\Contracts\OrderIdGenerator;
use ReinVanOyen\Copia\Contracts\OrderCreator;
use ReinVanOyen\Copia\Contracts\Payment;
use ReinVanOyen\Copia\Models\Order;
use ReinVanOyen\Copia\Order\DefaultOrderIdGenerator;
use ReinVanOyen\Copia\Payment\NullPayment;
use ReinVanOyen\Copia\Order\DefaultOrderCreator;

class CopiaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/copia.php', 'copia');

        $this->app->singleton(CartManager::class, CartManager::class);
        $this->app->bind(CartStorage::class, config('copia.cart.storage'));

        $this->app->bind(Payment::class, config('copia.payment', NullPayment::class));
        $this->app->bind(OrderCreator::class, config('copia.order.creator', DefaultOrderCreator::class));
        $this->app->bind(OrderIdGenerator::class, config('copia.order.id_generator', DefaultOrderIdGenerator::class));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishes();
        }

        $this->registerListeners();
    }

    /**
     *
     */
    private function registerPublishes()
    {
        $this->publishes([
            __DIR__.'/../config/copia.php' => config_path('copia.php'),
        ], 'copia-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'copia-migrations');
    }

    /**
     * @return void
     */
    private function registerListeners()
    {
        Event::listen('copia.order.created', function (Order $order) {
            app(Payment::class)->pay($order);
        });

        Event::listen('copia.payment.complete', function (Order $order) {

            foreach ($order->orderItems as $item) {

                $buyable = $item->buyable;
                $qty = $item->quantity;

                $stock = $buyable->getBuyableStockWorker();
                $stock->decrement($buyable, $qty);

                app(CartManager::class)->clear();
            }
        });
    }
}

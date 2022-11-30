<?php

namespace ReinVanOyen\Copia;

use Illuminate\Support\ServiceProvider;
use ReinVanOyen\Copia\Cart\CartManager;
use ReinVanOyen\Copia\Contracts\Payment;

class CopiaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartManager::class, CartManager::class);
        $this->app->bind(Payment::class, config('copia.payment'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishes();
        //$this->loadRoutes();
        //$this->loadViews();
        //$this->loadTranslations();
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
     *
     */
    private function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     *
     */
    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cmf');
    }

    /**
     *
     */
    private function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cmf');
    }
}

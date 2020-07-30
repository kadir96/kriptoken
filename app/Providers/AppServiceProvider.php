<?php

namespace App\Providers;

use App\Exchange\Converter;
use App\Exchange\Exchanger;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Set the decimal precision to be used in all conversions and balance operations
        bcscale(config('exchange.decimal_precision'));

        $this->app->bind(Converter::class, function ($app) {
            return new Converter(config('exchange.decimal_precision'));
        });

        $this->app->bind(Exchanger::class, function ($app) {
            return new Exchanger($app->make(Converter::class), config('exchange.commission_percent'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

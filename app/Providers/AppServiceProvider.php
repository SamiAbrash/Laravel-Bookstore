<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\OrderItems;
use App\Observers\OrderItemsObserver;
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        OrderItems::observe(OrderItemsObserver::class);
    }
}

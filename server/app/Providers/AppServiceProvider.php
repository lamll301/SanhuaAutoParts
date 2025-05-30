<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AddressServiceProvider::class);
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }
}

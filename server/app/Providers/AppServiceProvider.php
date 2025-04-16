<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\Promotion;
use App\Observers\PromotionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Promotion::observe(PromotionObserver::class);
    }
}

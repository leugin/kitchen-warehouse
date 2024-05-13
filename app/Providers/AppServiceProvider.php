<?php

namespace App\Providers;

use App\Foundation\FarmerMarket\Contract\FarmerMarketService;
use App\Foundation\FarmerMarket\Infrastructure\FakerFarmerMarket;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FarmerMarketService::class, FakerFarmerMarket::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

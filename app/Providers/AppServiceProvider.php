<?php

namespace App\Providers;

use App\Contracts\GeolocationProvider;
use App\Services\IpApiGeolocationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeolocationProvider::class, IpApiGeolocationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

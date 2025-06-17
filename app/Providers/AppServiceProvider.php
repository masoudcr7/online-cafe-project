<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Tighten\Ziggy\ZiggyServiceProvider; // <-- این خط دیگر نیاز نیست اگر auto-discovery کار کند

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // این خط را حذف کنید، چون Laravel 12 خودش auto-discovery می کند.
        // $this->app->register(ZiggyServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

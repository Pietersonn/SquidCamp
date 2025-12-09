<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aktifkan pagination bootstrap 4
        Paginator::useBootstrapFour();

        // Jika domain tunnel *trycloudflare*
        if (str_contains(request()->url(), 'trycloudflare.com')) {
            URL::forceScheme('https');
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
            return; 
        }

        // Environment production biasa
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }
    }
}

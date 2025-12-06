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

  /**public function boot(): void
  {
    \Illuminate\Support\Facades\App::setLocale('id');
    \Carbon\Carbon::setLocale('id');
    Paginator::useBootstrapFour();
  }
  */

  public function boot(): void
    {
        // [FIX] Paksa HTTPS jika di Production atau ngrok/cloudflare
        if($this->app->environment('production') || str_contains(request()->url(), 'trycloudflare.com')) {
            URL::forceScheme('https');
        }
    }
}

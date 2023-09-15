<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Attributes\Url;

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
        if (env("PROXY_URL")) {
            $proxyUrl = str(env("PROXY_URL"));
            URL::forceScheme($proxyUrl->lower()->startsWith('https') ? 'https' : 'http');
            Url::forceRootUrl(env("PROXY_URL"));
        }
    }
}

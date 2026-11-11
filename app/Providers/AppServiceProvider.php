<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\Middleware\WindowsAuthenticate;

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
        if ($proxyUrl = config('app.proxy_url')) {
            URL::forceScheme(str($proxyUrl)->lower()->startsWith('https') ? 'https' : 'http');
            URL::forceRootUrl($proxyUrl);
        }

        // Limit per monitor (secret) instead of per ip, many monitors can share one ip (NAT)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->input('secret') ?: $request->ip());
        });

        if (config('app.sso_enabled')) {
            WindowsAuthenticate::serverKey(config('app.sso_http_header_user_key'));
        }

        if (config('app.sso_bypass_domain_verification')) {
            WindowsAuthenticate::bypassDomainVerification();
        }
    }
}

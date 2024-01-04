<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LdapRecord\Laravel\Middleware\WindowsAuthenticate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        if(config('app.sso_enabled')) {
            WindowsAuthenticate::serverKey(config('app.sso_http_header_user_key'));
        }

        if(config('app.sso_bypass_domain_verification')) {
            WindowsAuthenticate::bypassDomainVerification();
        }
    }
}

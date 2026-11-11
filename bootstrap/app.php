<?php

use App\Http\Middleware\CheckForFirstUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use LdapRecord\Laravel\Middleware\WindowsAuthenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // The app usually runs behind a reverse proxy (see docker-compose.example).
        // Trusting it lets $request->ip() return the real client ip of the monitors.
        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES', '*'),
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB,
        );

        $middleware->web(append: [
            WindowsAuthenticate::class,
        ]);

        $middleware->throttleApi();

        $middleware->alias([
            'check_for_first_user' => CheckForFirstUser::class,
        ]);

        $middleware->redirectUsersTo('/devices');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

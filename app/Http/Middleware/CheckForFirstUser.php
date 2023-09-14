<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class CheckForFirstUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(\App\Models\User::count() === 1)
        {
            if(auth()->user() && auth()->user()->hasRole('Super Administrator')) {
                return $next($request);
            } elseif(auth()->user() && !auth()->user()->hasRole('Super Administrator')) {
                auth()->user()->assignRole(Role::findByName('Super Administrator')->first()->id);
            }
        }

        return $next($request);
    }
}

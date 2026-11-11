<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForFirstUser
{
    /**
     * The very first user of the application becomes Super Administrator.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->hasRole('Super Administrator') && User::count() === 1) {
            // Role is resolved with the user's guard (sanctum)
            $user->syncRoles(['Super Administrator']);
        }

        return $next($request);
    }
}

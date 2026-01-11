<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (Features::enabled(Features::twoFactorAuthentication()) &&
            $user && $user->twoFactorAuthEnabled() &&
            !$request->is('two-factor*')) {
            return redirect()->route('admin.login');
        }else{
            return $next($request);

        }

    }
}

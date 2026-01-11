<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

//        foreach ($guards as $guard) {
//            if (Auth::guard($guard)->check()) {
//                // Check if two-factor auth is enabled and if we are in the challenge phase
//
//                // Allow access to the two-factor challenge route if user is not fully authenticated
//                if (Features::enabled(Features::twoFactorAuthentication())  && !is_null(Auth::user()->two_factor_secret))
//                {
//                    return $next($request);
//                }
//                return redirect(RouteServiceProvider::HOME);
//            }
//        }
        checkTerminatingEmployees();

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}

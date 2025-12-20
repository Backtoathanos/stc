<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // Check if user is active
            if ($user->status !== 'active') {
                Auth::logout();
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Your account is inactive'], 403);
                }
                return redirect('/stc/stc_payroll/login')->with('error', 'Your account is inactive. Please contact administrator.');
            }
            return $next($request);
        }
        
        // Check for remember token cookie
        $rememberToken = Cookie::get('remember_token');
        if ($rememberToken) {
            $user = \App\User::where('remember_token', $rememberToken)->first();
            if ($user && $user->status === 'active') {
                Auth::login($user);
                return $next($request);
            }
        }
        
        // If not authenticated and no valid remember token, redirect to login
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        return redirect('/stc/stc_payroll/login');
    }
}


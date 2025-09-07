<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for guest users (they will be redirected to login)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Check if user has any roles
        if ($user->roles()->count() === 0) {
            // User has no roles, redirect to access denied page
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki role yang diperlukan untuk mengakses sistem. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}

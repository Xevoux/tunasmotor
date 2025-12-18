<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectToMainLogin
{
    /**
     * Handle an incoming request.
     * Redirect to main login page if user is not authenticated or not an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, redirect to main login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // If user is authenticated but not admin, redirect to home with error
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}


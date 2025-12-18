<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerRole
{
    /**
     * Handle an incoming request.
     * Redirect admin users to admin panel if they try to access customer pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated and is admin, redirect to admin panel
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect('/admin')->with('error', 'Admin hanya dapat mengakses halaman admin panel.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is an AJAX request or expects JSON response
        if (!$request->ajax() && !$request->wantsJson() && !$request->expectsJson()) {
            // If it's not an AJAX request, redirect to home page
            // This prevents direct browser access to API endpoints
            return redirect()->route('home');
        }
        
        return $next($request);
    }
}

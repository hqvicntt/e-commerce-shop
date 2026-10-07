<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND has the 'admin' role
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Authorized! Allow the request to proceed to the next step
            return $next($request);
        }
        // Unauthorized! Redirect back to products list with an error message
        return redirect()->route('products.index')->with('error', 'Access denied. You must be an administrator.');
    }
}

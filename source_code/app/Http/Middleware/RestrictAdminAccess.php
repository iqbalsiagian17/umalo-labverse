<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in and has the 'admin' role
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Redirect admin users to the dashboard or another route
            return redirect()->route('dashboard')->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;

class LogVisit
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
        // Log visits for guests (not logged in) and users with the "customer" role
        if (!Auth::check() || (Auth::check() && Auth::user()->role === 'customer')) {
            Visit::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'visited_at' => now(),
                'ip_address' => $request->ip(),
            ]);
        }

        return $next($request);
    }
}

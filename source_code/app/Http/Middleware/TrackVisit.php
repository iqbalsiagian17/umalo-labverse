<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visit;

class TrackVisit
{
    public function handle($request, Closure $next)
    {
        // Pastikan rute adalah 'home' dan pengguna bukan admin
        if ($request->route()->getName() === 'home' && auth()->check() && !auth()->user()->is_admin) {
            Visit::create([
                'user_id' => auth()->id(),
                'visited_at' => now(),
                'ip_address' => $request->ip(),
            ]);
        }

        return $next($request);
    }
}
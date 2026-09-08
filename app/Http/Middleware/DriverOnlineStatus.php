<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\DriverStatus;

class DriverOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'driver') {
            $driver = DriverStatus::where('user_id', Auth::id())->first();
            if ($driver && $driver->is_online) {
                $driver->update([
                    'last_updated' => now()
                ]);
            }
        }
        
        return $next($request);
    }
}

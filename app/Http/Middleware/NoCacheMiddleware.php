<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheMiddleware
{
    /**
     * Handle an incoming request.
     * Prevent browser caching for authenticated pages
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Set cache control headers untuk mencegah caching
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
    }
}

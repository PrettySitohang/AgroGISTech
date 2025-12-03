<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request and verify role(s).
     * Usage: ->middleware('role:super_admin') or 'role:editor|penulis'
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowed = explode('|', $roles);
        if (! in_array($user->role, $allowed)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}

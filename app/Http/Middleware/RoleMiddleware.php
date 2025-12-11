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
        
        // Jika user tidak login, redirect ke login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Explode roles string menjadi array
        $allowed = array_map('trim', explode('|', $roles));
        
        // Debug: Log user role
        \Log::debug('RoleMiddleware Check', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'allowed_roles' => $allowed,
            'has_access' => in_array($user->role, $allowed)
        ]);

        // Check apakah user's role ada di allowed roles
        if (!in_array($user->role, $allowed)) {
            \Log::warning('Access Denied', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $allowed,
                'requested_path' => $request->path()
            ]);
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}

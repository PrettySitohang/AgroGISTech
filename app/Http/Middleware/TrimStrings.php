<?php
// app/Http/Middleware/TrimStrings.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrimStrings
{
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();
        array_walk_recursive($data, function (&$v) { if (is_string($v)) $v = trim($v); });
        $request->merge($data);
        return $next($request);
    }
}

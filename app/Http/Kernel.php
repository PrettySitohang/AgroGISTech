<?php
// app/Http/Kernel.php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Global middleware stack (minimal for this scaffold)
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class ?? \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class ?? \Illuminate\Auth\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}

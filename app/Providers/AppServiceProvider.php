<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure the 'role' middleware alias can be resolved from the container
        // (defensive binding in case middleware alias resolution becomes stale).
        $this->app->singleton('role', function ($app) {
            return $app->make(\App\Http\Middleware\RoleMiddleware::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        // Share site settings to all views
        View::composer('*', function ($view) {
            $siteName = Setting::get('site_name', config('app.name', 'AgroGISTech'));
            $logoPath = Setting::get('logo');
            
            $view->with([
                'siteName' => $siteName,
                'logoPath' => $logoPath,
            ]);
        });
    }
}

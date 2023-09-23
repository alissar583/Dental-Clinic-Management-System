<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware(['api','language'])
                ->prefix('api')
                ->group(base_path('routes/api.php'));

                Route::middleware(['auth:api','api', 'language','role:Admin'])
                ->prefix('v1/api/admin')
                ->group(base_path('routes/v1/admin.php'));

                Route::middleware(['auth:api','api', 'language','role:Doctor'])
                ->prefix('v1/api/doctor')
                ->group(base_path('routes/v1/doctor.php'));

                Route::middleware(['auth:api','api', 'language','role:Secretary'])
                ->prefix('v1/api/secretary')
                ->group(base_path('routes/v1/secretary.php'));

                Route::middleware(['auth:api','api', 'language','role:Patient'])
                ->prefix('v1/api/patient')
                ->group(base_path('routes/v1/patient.php'));
                
                Route::middleware(['api', 'language'])
                ->prefix('v1/api')
                ->group(base_path('routes/v1/shared.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

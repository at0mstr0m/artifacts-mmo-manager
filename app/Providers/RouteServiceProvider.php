<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $path = base_path('routes/dev.php');
        if ('local' === env('APP_ENV') && file_exists($path)) {
            $this->loadRoutesFrom($path);
        }
    }
}

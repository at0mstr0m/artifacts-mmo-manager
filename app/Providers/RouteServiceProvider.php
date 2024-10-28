<?php

declare(strict_types=1);

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
        if (env('APP_ENV') === 'local' && file_exists($path)) {
            $this->loadRoutesFrom($path);
        }
    }
}

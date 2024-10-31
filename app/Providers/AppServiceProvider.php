<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ArtifactsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ArtifactsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}

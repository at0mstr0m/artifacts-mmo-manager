<?php

declare(strict_types=1);

use App\Http\Middleware\HandleInertiaRequests;
use App\Jobs\UpdateCurrentThings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->withSchedule(function (Schedule $schedule) {
        $schedule->job(UpdateCurrentThings::class)->everyMinute();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    })
    ->create();

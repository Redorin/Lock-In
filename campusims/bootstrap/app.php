<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES', '127.0.0.1,REMOTE_ADDR'),
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PREFIX
        );
        $middleware->alias([
            'ensure.not.checked.in' => \App\Http\Middleware\RedirectIfCheckedIn::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Auto-checkout every 30 minutes
        $schedule->command('checkins:auto-checkout')->everyThirtyMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

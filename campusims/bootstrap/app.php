<?php

// Add this inside your withSchedule() or bootstrap/app.php
// In Laravel 12, open bootstrap/app.php and add:

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
    })
    ->withSchedule(function (Schedule $schedule) {
        // Auto-checkout every 30 minutes
        $schedule->command('checkins:auto-checkout')->everyThirtyMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
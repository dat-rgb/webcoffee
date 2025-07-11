<?php

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
        // Middlewares here
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handlers here
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('orders:check-expired')->everyMinute();
    })
    ->create();

<?php

use App\Http\Middleware\VerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Gloabal Middleware [apply on all routes globally]
        $middleware->append([
            'App\Http\Middleware\MarkNotificationAsRead' , 
            'App\Http\Middleware\VerifyApiKey' , 
        ]) ; 
        // Alisas Middleware [applay only for specific endpoints]
        $middleware->alias([
            'ability' => CheckForAnyAbility::class , 
            'verify_email' => VerifyEmail::class , 
        ]) ; 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
        $schedule->command('queue:work')->everyMinute();
        $schedule->command('queue:restart')->everyFiveMinutes();
  })->create();

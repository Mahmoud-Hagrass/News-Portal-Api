<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\GenerateUserTokens;
use App\Listeners\SendOtpVerificationMail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            UserRegistered::class , 
            SendOtpVerificationMail::class , 
        ) ; 

        Event::listen(
            UserRegistered::class , 
            GenerateUserTokens::class , 
        ) ; 
    }
}

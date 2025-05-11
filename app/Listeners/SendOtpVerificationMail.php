<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\SendOtpVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOtpVerificationMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $event->user->notify(new SendOtpVerificationNotification()) ; 
    }
}

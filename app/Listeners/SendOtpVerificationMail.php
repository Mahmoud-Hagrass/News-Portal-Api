<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\SendVerificationEmail;
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
        dispatch(new SendVerificationEmail($event->user)) ; 
    }
}

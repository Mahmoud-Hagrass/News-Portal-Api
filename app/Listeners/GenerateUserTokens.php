<?php

namespace App\Listeners;

use App\Enums\TokenAbility;
use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;

class GenerateUserTokens
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
        $event->access_token   = $event->user->createToken('access-token' , [TokenAbility::ACCESS_TOKEN->value] ,   Carbon::now()->addMinutes(config('sanctum.access_token_expiration')))->plainTextToken ;
        $event->refresh_token  = $event->user->createToken('refresh-token' , [TokenAbility::REFRESH_TOKEN->value] , Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')))->plainTextToken ;
        
    }
}

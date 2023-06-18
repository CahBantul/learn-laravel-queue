<?php

namespace App\Listeners;

use App\Notifications\SendWarnEmailToUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetLastLoginAt implements ShouldQueue
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
    public function handle(object $event): void
    {
        $user = $event?->user;
        $needToBeWarned = $user?->last_login_at?->diffIndays() >= 15;
        
        if($needToBeWarned && $user?->last_login_at)
        {
            $user->notify(new SendWarnEmailToUser());
        }

        $user->forceFill(['last_login_at' => now()])->save();
    }
}

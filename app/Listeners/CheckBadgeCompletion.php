<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckBadgeCompletion
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AchievementUnlocked  $event
     * @return void
     */
    public function handle(AchievementUnlocked $event)
    {
        $newAmount = $event->user->achievements->count();

        $badges = Badge::where([
            'achievement_amount' => $newAmount
        ]);

        $badges->each(function($badge) use ($event) {
            
            //Create achiement unlocked
            $badge->users()->attach($event->user->id);

            //Fire event
            BadgeUnlocked::dispatch($badge, $event->user);
        });


    }
}

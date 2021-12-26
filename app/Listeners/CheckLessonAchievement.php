<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckLessonAchievement
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
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $newAmount = $event->user->lessons->count();
        
        //Check if there are achievements with new amount
        $achievements = Achievement::where([
            'model' => 'Lesson',
            'milestone' => $newAmount
        ]);

        $achievements->each(function($achievement) use ($event) {
            
            //Create achiement unlocked
            $achievement->users()->attach($event->user->id);

            //Fire event
            AchievementUnlocked::dispatch($achievement, $event->user);
        });
    }
}

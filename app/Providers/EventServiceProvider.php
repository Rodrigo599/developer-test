<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Listeners\CheckBadgeCompletion;
use App\Listeners\CheckCommentAchievement;
use App\Listeners\CheckLessonAchievement;
use App\Models\Achievement;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            CheckCommentAchievement::class
        ],
        LessonWatched::class => [
            CheckLessonAchievement::class
        ],
        AchievementUnlocked::class => [
            CheckBadgeCompletion::class
        ],
        BadgeUnlocked::class => [
            //
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

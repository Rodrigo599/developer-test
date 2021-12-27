<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckCommentAchievement
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
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        //Get Amount of comments by user
        $newAmount = Comment::where([
            'user_id' => $event->comment->user_id
        ])->count();
        
        //Check if there are achievements with new amount
        $achievements = Achievement::where([
            'model' => 'Comment',
            'milestone' => $newAmount
        ]);

        $achievements->each(function($achievement) use ($event) {
            
            //Create achiement unlocked
            $achievement->users()->attach($event->comment->user_id);

            //Fire event
            AchievementUnlocked::dispatch($achievement, User::findOrFail($event->comment->user_id));
        });
    }
}

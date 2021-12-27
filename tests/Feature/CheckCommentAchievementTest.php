<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\CheckCommentAchievement;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckCommentAchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_achievement_listener_is_attached_to_event()
    {
        Event::fake();
        Event::assertListening(
            CommentWritten::class,
            CheckCommentAchievement::class
        );
    }

    public function test_comment_triggering_achievement()
    {
        Event::fake();

        $achievement = Achievement::factory()->create([
            'name' => 'One Comment Written',
            'model' => 'Comment',
            'milestone' => 1
        ]);

        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id
        ]);

        $event = new CommentWritten($comment);
        $listener = new CheckCommentAchievement();
        $listener->handle($event);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertDatabaseHas('achievement_user', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id
        ]);
    }

    public function test_comment_without_triggering_achievement()
    {
        Event::fake();

        Achievement::factory()->create([
            'model' => 'Comment',
            'milestone' => 10
        ]);

        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id
        ]);

        $event = new CommentWritten($comment);
        $listener = new CheckCommentAchievement();
        $listener->handle($event);

        $this->assertDatabaseMissing('achievement_user', [
            'user_id' => $user->id,
        ]);

        Event::assertNotDispatched(AchievementUnlocked::class);
    }
}

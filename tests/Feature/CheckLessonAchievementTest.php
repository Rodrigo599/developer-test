<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\CheckCommentAchievement;
use App\Listeners\CheckLessonAchievement;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckLessonAchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_lesson_achievement_is_attached_to_event()
    {
        Event::fake();
        Event::assertListening(
            LessonWatched::class,
            CheckLessonAchievement::class
        );
    }

    public function test_watched_lesson_triggering_achievement()
    {
        Event::fake();

        $achievement = Achievement::factory()->create([
            'name' => 'One Lesson Watched',
            'model' => 'Lesson',
            'milestone' => 1
        ]);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $user->lessons()->attach($lesson->id, [
            'watched' => true
        ]);

        $event = new LessonWatched($lesson, $user);
        $listener = new CheckLessonAchievement();
        $listener->handle($event);

        Event::assertDispatched(AchievementUnlocked::class);

        $this->assertDatabaseHas('achievement_user', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id
        ]);
    }

    public function test_watched_lesson_without_triggering_achievement()
    {
        Event::fake();

        Achievement::factory()->create([
            'model' => 'Lesson',
            'milestone' => 10
        ]);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        
        $user->lessons()->attach($lesson->id, [
            'watched' => true
        ]);

        $event = new LessonWatched($lesson, $user);
        $listener = new CheckLessonAchievement();
        $listener->handle($event);

        $this->assertDatabaseMissing('achievement_user', [
            'user_id' => $user->id,
        ]);

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_unwatched_lesson_not_triggering_achievement()
    {
        Event::fake();

        $achievement = Achievement::factory()->create([
            'model' => 'Lesson',
            'milestone' => 1
        ]);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        
        $user->lessons()->attach($lesson->id, [
            'watched' => false
        ]);

        $event = new LessonWatched($lesson, $user);
        $listener = new CheckLessonAchievement();
        $listener->handle($event);

        $this->assertDatabaseMissing('achievement_user', [
            'user_id' => $user->id,
            'achievement_id' => $achievement->id
        ]);

        Event::assertNotDispatched(AchievementUnlocked::class);
    }
}

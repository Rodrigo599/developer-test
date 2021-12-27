<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\CheckBadgeCompletion;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckBadgeCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_badge_completion_listener_is_attached_to_event()
    {
        Event::fake();
        Event::assertListening(
            AchievementUnlocked::class,
            CheckBadgeCompletion::class
        );
    }

    public function test_achievement_trigger_badge_upgrade()
    {
        Event::fake();

        $user = User::factory()
                    ->hasAchievements(9)
                    ->hasBadges(1, [
                        'name' => 'Current Badge',
                        'achievement_amount' => 9
                    ])
                    ->create();

        $achievement = Achievement::factory()->create();

        $badge = Badge::factory()->create([
            'name' => '10 Achievement Badge',
            'achievement_amount' => 10
        ]);

        //Add 10th achievement to trigger badge completion
        $user->achievements()->attach($achievement->id);

        $event = new AchievementUnlocked($achievement, $user);
        $listener = new CheckBadgeCompletion();
        $listener->handle($event);

        $this->assertDatabaseHas('badge_user', [
            'user_id' => $user->id,
            'badge_id' => $badge->id
        ]);

        $this->assertEquals('10 Achievement Badge', $user->currentBadge()->name);

        Event::assertDispatched(BadgeUnlocked::class);
    }

    public function test_achievement_does_not_trigger_badge_upgrade()
    {
        Event::fake();

        $user = User::factory()
                    ->hasAchievements(5)
                    ->hasBadges(1, [
                        'name' => 'Current Badge',
                        'achievement_amount' => 5
                    ])
                    ->create();

        $achievement = Achievement::factory()->create();

        $badge = Badge::factory()->create([
            'name' => '10 Achievement Badge',
            'achievement_amount' => 10
        ]);

        //Add 6th achievement that does not trigger badge completion
        $user->achievements()->attach($achievement->id);

        $event = new AchievementUnlocked($achievement, $user);
        $listener = new CheckBadgeCompletion();
        $listener->handle($event);

        $this->assertDatabaseMissing('badge_user', [
            'user_id' => $user->id,
            'badge_id' => $badge->id
        ]);

        $this->assertEquals('Current Badge', $user->currentBadge()->name);

        Event::assertNotDispatched(BadgeUnlocked::class);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Badge;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_structure()
    {
        $user = User::factory()
            ->hasAchievements(1, [
                'milestone' => 1
            ])
            ->hasBadges(1, [
                'name' => '1 Achievement Badge',
                'achievement_amount' => 1
            ])
            ->create();

        //Just creating more data for fields
        Achievement::factory()->create([
            'milestone' => 5
        ]);
        Badge::factory()->create([
            'achievement_amount' => 10
        ]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonStructure([
            'unlocked_achievements',
            'next_available_achievements',
            'current_badge',
            'next_badge',
            'remaing_to_unlock_next_badge'
        ]);

        $response->assertSuccessful();
    }

    public function test_new_user_has_initial_badge()
    {
        $badge = Badge::factory()->create([
            'name' => 'Beginner',
            'achievement_amount' => 0
        ]);

        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonPath('current_badge', 'Beginner');
        $response->assertSuccessful();
    }

    public function test_show_next_badge_correctly()
    {
        Badge::factory()->create([
            'name' => 'Next Badge',
            'achievement_amount' => 25
        ]);

        $user = User::factory()->hasAchievements(10)->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonPath('remaing_to_unlock_next_badge', 15);
        $response->assertJsonPath('next_badge', 'Next Badge');
        $response->assertSuccessful();
    }

    public function test_show_current_badge_correctly()
    {
        $olderBadge = Badge::factory()->create([
            'name' => 'Older Badge',
            'achievement_amount' => 5
        ]);

        $currentBadge = Badge::factory()->create([
            'name' => 'Current Badge',
            'achievement_amount' => 10
        ]);

        $user = User::factory()->hasAchievements(10)->create();
        $user->badges()->attach([$currentBadge->id, $olderBadge->id]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonPath('current_badge', 'Current Badge');
        $response->assertSuccessful();
    }

    public function test_show_correctly_next_available_achievements()
    {
        $user = User::factory()->hasAchievements(1, [
            'name' => 'Previous Achievement',
            'milestone' => 1,
            'model' => 'Comment'
        ])->create();
        
        Achievement::factory()->create([
            'name' => 'Next Achievement',
            'milestone' => 2,
            'model' => 'Comment'
        ]);

        Achievement::factory()->create([
            'name' => 'Far Achievement',
            'milestone' => 4,
            'model' => 'Comment'
        ]);
        
        Achievement::factory()->create([
            'name' => 'Next Achievement for Lesson',
            'milestone' => 1,
            'model' => 'Lesson'
        ]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonPath('next_available_achievements', [
            'Next Achievement for Lesson',
            'Next Achievement',
        ]);
    }

    public function test_show_unlocked_achievements() 
    {
        $user = User::factory()->hasAchievements(5)->create();

        $achievement = Achievement::factory()->create([
            'name' => 'Another Achievement',
            'milestone' => 200
        ]);

        $user->achievements()->attach($achievement->id);

        $response = $this->get("/users/{$user->id}/achievements");

        $data = ((array)json_decode($response->getContent()));

        //Assert has 6 arrays, with one being a certain achievement
        $this->assertEquals(6, sizeof($data['unlocked_achievements']));
        $this->assertTrue(in_array($achievement->name, $data['unlocked_achievements']));
    }
}

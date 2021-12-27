<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LessonSeeder::class,
            BadgeSeeder::class,
            AchievementSeeder::class,
            UserSeeder::class,
            CommentSeeder::class,
        ]);
    }
}

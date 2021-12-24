<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('achievements')->insert([
            [
                'name' => 'First Lesson Watched',
                'model' => 'Lesson',
                'milestone' => 1,
            ], 
            [
                'name' => '5 Lessons Watched',
                'model' => 'Lesson',
                'milestone' => 5,
            ],
            [
                'name' => '10 Lessons Watched',
                'model' => 'Lesson',
                'milestone' => 10,
            ],
            [
                'name' => '25 Lessons Watched',
                'model' => 'Lesson',
                'milestone' => 25,
            ],
            [
                'name' => '50 Lessons Watched',
                'model' => 'Lesson',
                'milestone' => 50,
            ],
            [
                'name' => 'First Comment Written',
                'model' => 'Comment',
                'milestone' => 1,
            ],
            [
                'name' => '3 Comments Written',
                'model' => 'Comment',
                'milestone' => 3,
            ],
            [
                'name' => '5 Comments Written',
                'model' => 'Comment',
                'milestone' => 5,
            ],
            [
                'name' => '10 Comments Written',
                'model' => 'Comment',
                'milestone' => 10,
            ],
            [
                'name' => '20 Comments Written',
                'model' => 'Comment',
                'milestone' => 20,
            ]
        ]);
    }
}

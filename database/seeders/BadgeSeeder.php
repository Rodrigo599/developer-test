<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('badges')->insert([
            [
                'name' => 'Beginner',
                'achievement_amount' => 0
            ],
            [
                'name' => 'Intermediate',
                'achievement_amount' => 4
            ],
            [
                'name' => 'Advanced',
                'achievement_amount' => 8
            ],
            [
                'name' => 'Master',
                'achievement_amount' => 10
            ]
        ]);
    }
}

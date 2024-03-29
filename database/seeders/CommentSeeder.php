<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory()
            ->count(20)
            ->create();
    }
}

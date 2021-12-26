<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Http\Resources\AchievementResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserAchievement;
use App\Models\Badge;

class AchievementsController extends Controller
{
    public function index(User $user)
    {

        $comment = Comment::where('id', 1);
        CommentWritten::dispatch();
        dd("foi");
        return new UserAchievement($user->load([
            'achievements',
            'badges'
        ]));
    }
}

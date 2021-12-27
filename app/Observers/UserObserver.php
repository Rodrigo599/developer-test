<?php

namespace App\Observers;

use App\Models\Badge;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        
        $badge = Badge::where([
            'achievement_amount' => 0
        ])->first();

        if($badge) {
            $user->badges()->attach($badge->id);
        }
    }
}

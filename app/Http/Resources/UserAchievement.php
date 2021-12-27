<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAchievement extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'unlocked_achievements' => $this->achievements->pluck('name'),
            'next_available_achievements' => $this->availableAchievements()->pluck('name'),
            'current_badge' => $this->currentBadge() ? $this->currentBadge()->name : null,
            'next_badge' => $this->nextBadge() ? $this->nextBadge()->name : null,
            'remaing_to_unlock_next_badge' => $this->nextBadge() ? ($this->nextBadge()->achievement_amount - $this->achievements->count()) : null,
        ];
    }
}

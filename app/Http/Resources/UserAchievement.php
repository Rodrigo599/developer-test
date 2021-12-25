<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAchievement extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'unlocked_achievements' => $this->whenLoaded('achievements'),
            'next_available_achievements' => $this->availableAchievements(),
            'current_badge' => $this->currentBadge(),
            'next_badge' => $this->nextBadge(),
            'remaing_to_unlock_next_badge' => $this->nextBadge() ? ($this->nextBadge()->achievement_amount - $this->achievements->count()) : null,
        ];
    }
}

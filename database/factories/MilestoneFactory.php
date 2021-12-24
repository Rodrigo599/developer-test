<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'achievement_id' => Achievement::factory(),
            'model' => $this->faker->randomElement(Milestone::MODELS_ARRAY),
            'milestone' => $this->faker->randomNumber(1, 100),
        ];
    }
}

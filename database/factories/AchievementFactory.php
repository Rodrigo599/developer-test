<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'model' => $this->faker->randomElement(Achievement::MODELS_ARRAY),
            'milestone' => $this->faker->randomNumber(1, 100),
        ];
    }
}

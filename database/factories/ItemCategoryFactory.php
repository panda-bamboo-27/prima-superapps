<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemCategory>
 */
class ItemCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_ids = User::all()->pluck('id')->toArray();
        
        return [
            'name'  => fake()->unique()->word(),
            'description'   => fake()->realText(80),
            'user_id'   => fake()->randomElement($user_ids)
        ];
    }

    public function deleted(): static{
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}

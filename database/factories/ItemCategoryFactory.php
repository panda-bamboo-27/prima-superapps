<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name'  => fake()->unique()->word(),
            'description'   => fake()->realText(80)
        ];
    }

    public function deleted(): static{
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vendor;
use App\Models\ItemCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vendor_ids = Vendor::all()->pluck('id')->toArray();
        $item_category_ids = ItemCategory::all()->pluck('id')->toArray();
        $user_ids = User::all()->pluck('id')->toArray();
        
        $vendor_item_code = "VI" . fake()->unique()->randomNumber(9,true);
        return [
            'name' => fake()->catchPhrase(),
            'description' => fake()->bs(),
            'price_per_unit' => fake()->randomNumber(5,true),
            'unit'  => fake()->word(),
            'vendor_item_code'  => $vendor_item_code,
            'vendor_item_category' => fake()->jobTitle(),
            'vendor_id' => fake()->randomElement($vendor_ids),
            'item_category_id' => fake()->randomElement($item_category_ids),
            'user_id'   => fake()->randomElement($user_ids),
        ];
    }

    public function deleted(): static{
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}

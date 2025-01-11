<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vendor_code = "V". fake()->unique()->randomNumber(9,true);
        return [
            'vendor_code'   => $vendor_code,
            'vendor_name'   => fake()->company(),
            'address' => fake()->address(),
            'contact_phone_1' => fake()->phoneNumber(),
            'contact_phone_2' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
        ];
    }

    public function deleted(): static{
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}

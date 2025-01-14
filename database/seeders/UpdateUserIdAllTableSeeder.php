<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Vendor;

class UpdateUserIdAllTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $user_ids = User::all()->pluck('id')->toArray();

        $item_categories = ItemCategory::all();
        $items = Item::all();
        $vendors = Vendor::all();

        foreach ($item_categories as $item_category) {
            $item_category->user_id = $faker->randomElement($user_ids);
            $item_category->save();
        }
        
        foreach ($items as $item) {
            $item->user_id = $faker->randomElement($user_ids);
            $item->save();
        }

        foreach ($vendors as $vendor) {
            $vendor->user_id = $faker->randomElement($user_ids);
            $vendor->save();
        }
    }
}

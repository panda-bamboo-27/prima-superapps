<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Vendor;
use App\Models\Item;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Man from nowhere',
            'email' => 'johndoe@pi.com',
            'password'  => 'arisalsaila',
            'role'  => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Woman from nowhere',
            'email' => 'janedoe@pi.com',
            'password'  => 'arisalsaila',
            'role'  => 'guest'
        ]);

        Vendor::factory(10)->create();
        ItemCategory::factory(10)->create();
        Item::factory(60)->create();
    }
}

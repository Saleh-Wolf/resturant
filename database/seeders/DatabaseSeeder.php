<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    $this->call([
        DemoUsersSeeder::class,
        SectionSeeder::class,
        CategorySeeder::class,
        SubcategorySeeder::class,
        RestaurantTableSeeder::class,
        MenuItemSeeder::class,
        OfferSeeder::class,
        IngredientSeeder::class,
        MenuItemIngredientSeeder::class,
    ]);
}
}

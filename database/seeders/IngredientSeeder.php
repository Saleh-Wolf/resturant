<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [

            [
                'name' => 'Cheese',
                'unit' => 'gram',
                'current_stock' => 5000,
                'minimum_stock' => 500,
                'cost_per_unit' => 1.50,
                'is_active' => true,
            ],

            [
                'name' => 'Dough',
                'unit' => 'gram',
                'current_stock' => 10000,
                'minimum_stock' => 1000,
                'cost_per_unit' => 0.30,
                'is_active' => true,
            ],

            [
                'name' => 'Tomato Sauce',
                'unit' => 'gram',
                'current_stock' => 3000,
                'minimum_stock' => 300,
                'cost_per_unit' => 0.50,
                'is_active' => true,
            ],

            [
                'name' => 'Chicken',
                'unit' => 'gram',
                'current_stock' => 8000,
                'minimum_stock' => 1000,
                'cost_per_unit' => 2.50,
                'is_active' => true,
            ],

            [
                'name' => 'Beef',
                'unit' => 'gram',
                'current_stock' => 6000,
                'minimum_stock' => 1000,
                'cost_per_unit' => 3.50,
                'is_active' => true,
            ],

            [
                'name' => 'Potatoes',
                'unit' => 'gram',
                'current_stock' => 7000,
                'minimum_stock' => 500,
                'cost_per_unit' => 0.40,
                'is_active' => true,
            ],

            [
                'name' => 'Cooking Oil',
                'unit' => 'ml',
                'current_stock' => 10000,
                'minimum_stock' => 1000,
                'cost_per_unit' => 0.20,
                'is_active' => true,
            ],

            [
                'name' => 'Cola',
                'unit' => 'piece',
                'current_stock' => 200,
                'minimum_stock' => 20,
                'cost_per_unit' => 8,
                'is_active' => true,
            ],

        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(
                ['name' => $ingredient['name']],
                $ingredient
            );
        }
    }
}
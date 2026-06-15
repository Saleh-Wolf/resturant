<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class MenuItemIngredientSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            'Margherita Pizza' => [
                'Cheese' => 100,
                'Dough' => 250,
                'Tomato Sauce' => 80,
            ],
            'Chicken Pizza' => [
                'Cheese' => 100,
                'Dough' => 250,
                'Tomato Sauce' => 80,
                'Chicken' => 120,
            ],
            'Beef Burger' => [
                'Beef' => 150,
                'Cheese' => 30,
            ],
            'Chicken Burger' => [
                'Chicken' => 150,
                'Cheese' => 30,
            ],
            'Fried Chicken Meal' => [
                'Chicken' => 250,
                'Potatoes' => 200,
                'Cooking Oil' => 50,
            ],
            'French Fries' => [
                'Potatoes' => 200,
                'Cooking Oil' => 30,
            ],
            'Cola Can' => [
                'Cola' => 1,
            ],
        ];

        foreach ($recipes as $menuItemName => $ingredients) {
            $menuItem = MenuItem::where('name', $menuItemName)->first();

            if (!$menuItem) {
                continue;
            }

            $syncData = [];

            foreach ($ingredients as $ingredientName => $quantityRequired) {
                $ingredient = Ingredient::where('name', $ingredientName)->first();

                if (!$ingredient) {
                    continue;
                }

                $syncData[$ingredient->id] = [
                    'quantity_required' => $quantityRequired,
                ];
            }

            $menuItem->ingredients()->sync($syncData);
        }
    }
}
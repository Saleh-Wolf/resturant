<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Margherita Pizza',
                'category' => 'Pizza',
                'subcategory' => 'Classic Pizza',
                'price' => 130,
                'description' => 'Classic pizza with cheese and tomato sauce.',
            ],
            [
                'name' => 'Chicken Pizza',
                'category' => 'Pizza',
                'subcategory' => 'Chicken Pizza',
                'price' => 160,
                'description' => 'Pizza with chicken, cheese and tomato sauce.',
            ],
            [
                'name' => 'Beef Burger',
                'category' => 'Burgers',
                'subcategory' => 'Beef Burgers',
                'price' => 120,
                'description' => 'Beef burger with cheese.',
            ],
            [
                'name' => 'Chicken Burger',
                'category' => 'Burgers',
                'subcategory' => 'Chicken Burgers',
                'price' => 110,
                'description' => 'Chicken burger with cheese.',
            ],
            [
                'name' => 'Fried Chicken Meal',
                'category' => 'Fried Meals',
                'subcategory' => 'Chicken Meals',
                'price' => 180,
                'description' => 'Fried chicken meal with potatoes.',
            ],
            [
                'name' => 'French Fries',
                'category' => 'Fried Meals',
                'subcategory' => 'Side Dishes',
                'price' => 45,
                'description' => 'Crispy french fries.',
            ],
            [
                'name' => 'Cola Can',
                'category' => 'Soft Drinks',
                'subcategory' => 'Cans',
                'price' => 25,
                'description' => 'Cold cola can.',
            ],
        ];

        foreach ($items as $item) {
            $category = Category::where('name', $item['category'])->first();
            $subcategory = Subcategory::where('name', $item['subcategory'])->first();

            MenuItem::updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $category?->id,
                    'subcategory_id' => $subcategory?->id,
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image' => null,
                    'is_available' => true,
                ]
            );
        }
    }
}
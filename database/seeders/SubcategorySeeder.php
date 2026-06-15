<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Pizza' => ['Classic Pizza', 'Chicken Pizza'],
            'Burgers' => ['Beef Burgers', 'Chicken Burgers'],
            'Fried Meals' => ['Chicken Meals', 'Side Dishes'],
            'Soft Drinks' => ['Cans', 'Bottles'],
        ];

        foreach ($data as $categoryName => $subcategories) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($subcategories as $index => $subcategoryName) {
                Subcategory::updateOrCreate(
                    [
                        'category_id' => $category?->id,
                        'name' => $subcategoryName,
                    ],
                    [
                        'slug' => Str::slug($subcategoryName),
                        'description' => null,
                        'display_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
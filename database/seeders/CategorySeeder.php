<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $food = Section::where('name', 'Food')->first();
        $drinks = Section::where('name', 'Drinks')->first();

        $categories = [
            ['section_id' => $food?->id, 'name' => 'Pizza'],
            ['section_id' => $food?->id, 'name' => 'Burgers'],
            ['section_id' => $food?->id, 'name' => 'Fried Meals'],
            ['section_id' => $drinks?->id, 'name' => 'Soft Drinks'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'section_id' => $category['section_id'],
                    'slug' => Str::slug($category['name']),
                    'is_active' => true,
                ]
            );
        }
    }
}
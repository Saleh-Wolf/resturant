<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name' => 'Food', 'description' => 'Main food section', 'display_order' => 1],
            ['name' => 'Drinks', 'description' => 'Cold and hot drinks', 'display_order' => 2],
        ];

        foreach ($sections as $section) {
            Section::updateOrCreate(
                ['name' => $section['name']],
                [
                    'description' => $section['description'],
                    'display_order' => $section['display_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
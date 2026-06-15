<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantTableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T01', 'type' => 'indoor', 'min_capacity' => 1, 'max_capacity' => 2, 'location' => 'Main Hall'],
            ['table_number' => 'T02', 'type' => 'indoor', 'min_capacity' => 2, 'max_capacity' => 4, 'location' => 'Main Hall'],
            ['table_number' => 'T03', 'type' => 'indoor', 'min_capacity' => 4, 'max_capacity' => 6, 'location' => 'Main Hall'],
            ['table_number' => 'VIP01', 'type' => 'vip', 'min_capacity' => 2, 'max_capacity' => 6, 'location' => 'VIP Area'],
            ['table_number' => 'OUT01', 'type' => 'outdoor', 'min_capacity' => 2, 'max_capacity' => 4, 'location' => 'Outdoor Area'],
        ];

        foreach ($tables as $table) {
            RestaurantTable::updateOrCreate(
                ['table_number' => $table['table_number']],
                [
                    'type' => $table['type'],
                    'capacity' => $table['max_capacity'],
                    'min_capacity' => $table['min_capacity'],
                    'max_capacity' => $table['max_capacity'],
                    'location' => $table['location'],
                    'status' => 'available',
                    'notes' => null,
                    'qr_token' => Str::uuid(),
                ]
            );
        }
    }
}

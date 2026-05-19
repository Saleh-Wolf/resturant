<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'waiter@test.com'],
            [
                'name' => 'Waiter User',
                'password' => Hash::make('12345678'),
                'role' => 'waiter',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@test.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('12345678'),
                'role' => 'cashier',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kitchen@test.com'],
            [
                'name' => 'Kitchen User',
                'password' => Hash::make('12345678'),
                'role' => 'kitchen_staff',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@test.com', 'role' => 'admin'],
            ['name' => 'Waiter User', 'email' => 'waiter@test.com', 'role' => 'waiter'],
            ['name' => 'Cashier User', 'email' => 'cashier@test.com', 'role' => 'cashier'],
            ['name' => 'Kitchen User', 'email' => 'kitchen@test.com', 'role' => 'kitchen_staff'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => bcrypt('12345678'),
                ]
            );
        }
    }
}
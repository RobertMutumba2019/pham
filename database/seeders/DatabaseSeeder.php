<?php

namespace Database\Seeders;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
        User::updateOrCreate(
            ['user_name' => 'testuser'],
            [
                'user_surname' => 'User',
                'user_othername' => 'Test',
                'user_email' => 'test@example.com',
                'user_password' => bcrypt('password'),
                'user_role' => 1,
                'user_active' => 1,
                'check_number' => '123456',
                // add other required fields as needed
            ]
        );
                'user_email' => 'test@example.com',
                'user_password' => bcrypt('password'),
                'user_role' => 1,
                'user_active' => 1,
                'check_number' => '123456',
                // add other required fields as needed
            ]
        );
    }
}

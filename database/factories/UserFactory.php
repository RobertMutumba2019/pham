<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
        return [
            'user_name' => $this->faker->unique()->userName,
            'user_forgot_password' => 0,
            'user_active' => 1,
            'user_online' => 0,
            'user_name' => $this->faker->unique()->userName,
            'user_surname' => $this->faker->lastName,
            'user_othername' => $this->faker->firstName,
            'user_status' => 1,
            'user_email' => $this->faker->unique()->safeEmail,
            'user_telephone' => $this->faker->phoneNumber,
            'user_gender' => $this->faker->randomElement(['Male', 'Female']),
            'user_password' => bcrypt('password'),
            'user_date_added' => now(),
            'user_added_by' => null,
            'user_role' => '1',
            'user_forgot_password' => 0,
            'user_active' => 1,
            'user_online' => 0,
            'user_last_logged_in' => now(),
            'check_number' => $this->faker->unique()->numerify('######'),
        ];
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@tunasmotor.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@tunasmotor.com',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'city' => 'Bandung',
                'postal_code' => '40123',
                'email_verified_at' => now(),
            ]
        );

        // Create a demo customer
        User::updateOrCreate(
            ['email' => 'customer@tunasmotor.com'],
            [
                'name' => 'Demo Customer',
                'email' => 'customer@tunasmotor.com',
                'password' => Hash::make('customer123'),
                'role' => User::ROLE_CUSTOMER,
                'phone' => '081234567891',
                'address' => 'Jl. Customer No. 1',
                'city' => 'Bandung',
                'postal_code' => '40124',
                'email_verified_at' => now(),
            ]
        );
    }
}


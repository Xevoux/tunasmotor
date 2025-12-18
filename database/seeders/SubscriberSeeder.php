<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Subscriber::create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'subscribed_at' => now(),
            'is_active' => true,
        ]);

        \App\Models\Subscriber::create([
            'email' => 'inactive@example.com',
            'name' => 'Inactive User',
            'subscribed_at' => now()->subDays(30),
            'unsubscribed_at' => now()->subDays(5),
            'is_active' => false,
        ]);
    }
}

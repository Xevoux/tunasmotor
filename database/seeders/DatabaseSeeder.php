<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order: Admin first, then categories, then products
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CustomerServiceSeeder::class,
            SubscriberSeeder::class,
            NewsletterSeeder::class,
            SliderSeeder::class,
            HeroSeeder::class,
            FooterSeeder::class,
        ]);
    }
}

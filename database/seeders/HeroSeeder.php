<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hero;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hero::create([
            'deal_label' => 'REFRESHIN WINTER DEALS',
            'countdown_end_date' => now()->addDays(30), // 30 days from now
            'title' => 'LENGKAPI MOTOR ANDA.' . PHP_EOL . 'TEMUKAN SUKU CADANG' . PHP_EOL . 'TERBAIK.',
            'description' => 'Tingkatkan performa motor Anda dengan suku cadang berkualitas tinggi dan andal.' . PHP_EOL . 'Komponen terjui yang memberikan ketenangan saat berkendara.',
            'button_text' => 'Shop Now',
            'button_link' => null, // Will use default route
            'image_path' => 'heroes/hero.png', // Using hero.png as default
            'image_name' => 'hero.png',
            'alt_text' => 'Tunas Motor Hero Banner',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Footer;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Footer::create([
            'phone_number' => '+(62) 1234 5678 90',
            'email' => 'info@example.com',
            'address' => 'Cirebon',
            'map_embed_code' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.902!2d108.552!3d-6.717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNDMnMDEuMiJTIDEwOMKwMzMnMDcuMiJF!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'copyright_text' => '&copy; 2025 Tunas Motor. All rights reserved.',
            'is_active' => true,
        ]);
    }
}

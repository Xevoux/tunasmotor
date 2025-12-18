<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Note: In production, you would upload actual images to storage/app/public/sliders/
        // For now, we'll create placeholder entries that can be replaced with real images

        \App\Models\Slider::create([
            'title' => 'Premium Motorcycles Collection',
            'description' => 'Discover our exclusive range of high-performance motorcycles designed for every rider.',
            'image_path' => 'sliders/slider-1.jpg', // Placeholder path
            'image_name' => 'premium-motorcycles.jpg',
            'alt_text' => 'Premium motorcycles collection at Tunas Motor',
            'link_url' => route('products.index'),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        \App\Models\Slider::create([
            'title' => 'Expert Maintenance Services',
            'description' => 'Professional motorcycle maintenance and repair services by certified technicians.',
            'image_path' => 'sliders/slider-2.jpg', // Placeholder path
            'image_name' => 'maintenance-services.jpg',
            'alt_text' => 'Expert motorcycle maintenance services',
            'link_url' => null,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        \App\Models\Slider::create([
            'title' => 'Quality Spare Parts',
            'description' => 'Genuine spare parts and accessories for all motorcycle brands at competitive prices.',
            'image_path' => 'sliders/slider-3.jpg', // Placeholder path
            'image_name' => 'spare-parts.jpg',
            'alt_text' => 'Quality motorcycle spare parts',
            'link_url' => route('products.index'),
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}

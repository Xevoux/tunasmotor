<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Oli Motor
            [
                'category_id' => 1,
                'nama' => 'Castrol GTX High Mileage 5W-30 Synthetic Blend Motor Oil',
                'deskripsi' => 'Oli motor synthetic blend berkualitas tinggi untuk motor dengan jarak tempuh tinggi',
                'harga' => 681000,
                'harga_diskon' => null,
                'stok' => 24,
                'terjual' => 38,
                'gambar' => null,
                'is_new' => true,
                'diskon_persen' => 10,
            ],
            [
                'category_id' => 1,
                'nama' => 'Pennzoil Platinum Full Synthetic 5W-20 Motor Oil, 5 Quart',
                'deskripsi' => 'Oli motor full synthetic untuk performa maksimal',
                'harga' => 1200000,
                'harga_diskon' => null,
                'stok' => 30,
                'terjual' => 50,
                'gambar' => null,
                'is_new' => true,
                'diskon_persen' => null,
            ],
            
            // Lampu
            [
                'category_id' => 2,
                'nama' => 'Vauxhall Zafira MK2 2005-2014 Tail Back Rear Light Lamp Lens Right',
                'deskripsi' => 'Lampu belakang berkualitas original',
                'harga' => 450000,
                'harga_diskon' => null,
                'stok' => 15,
                'terjual' => 8,
                'gambar' => null,
                'is_new' => true,
                'diskon_persen' => 15,
            ],
            
            // Suspensi
            [
                'category_id' => 3,
                'nama' => 'Set Front Quick Complete Strut Coil Spring-Rear Shocks For 2016-2022',
                'deskripsi' => 'Set lengkap suspensi depan dan belakang',
                'harga' => 3500000,
                'harga_diskon' => null,
                'stok' => 8,
                'terjual' => 4,
                'gambar' => null,
                'is_new' => true,
                'diskon_persen' => 25,
            ],
            
            // Coolant
            [
                'category_id' => 7,
                'nama' => 'Zerex G05 Phosphate Free Antifreeze Coolant Concentrate 1 Gallon',
                'deskripsi' => 'Cairan pendingin mesin bebas fosfat',
                'harga' => 350000,
                'harga_diskon' => null,
                'stok' => 45,
                'terjual' => 67,
                'gambar' => null,
                'is_new' => true,
                'diskon_persen' => 5,
            ],
            
            // Filter
            [
                'category_id' => 6,
                'nama' => 'Oil Filter - Compatible with 2011 - 2022 Ford',
                'deskripsi' => 'Filter oli original kompatibel untuk berbagai tipe motor',
                'harga' => 1080000,
                'harga_diskon' => null,
                'stok' => 35,
                'terjual' => 28,
                'gambar' => null,
                'is_new' => false,
                'diskon_persen' => null,
            ],
            
            // Ban
            [
                'category_id' => 4,
                'nama' => 'Yokohama Geolandar X-CV All Season 255_45R20 105W XL',
                'deskripsi' => 'Ban premium all season dengan grip maksimal',
                'harga' => 4190000,
                'harga_diskon' => null,
                'stok' => 10,
                'terjual' => 20,
                'gambar' => null,
                'is_new' => false,
                'diskon_persen' => 20,
            ],
            [
                'category_id' => 4,
                'nama' => 'Catalytic converter high quality pass emissions test',
                'deskripsi' => 'Converter berkualitas tinggi',
                'harga' => 349000,
                'harga_diskon' => null,
                'stok' => 50,
                'terjual' => 17,
                'gambar' => null,
                'is_new' => false,
                'diskon_persen' => 25,
            ],
            
            // Velg
            [
                'category_id' => 5,
                'nama' => 'Touren TR60-3260 16X7 5X112_5X114.3 42H 72.62Cb',
                'deskripsi' => 'Velg racing premium dengan desain modern',
                'harga' => 2178000,
                'harga_diskon' => null,
                'stok' => 8,
                'terjual' => 5,
                'gambar' => null,
                'is_new' => false,
                'diskon_persen' => 20,
            ],
        ];

        foreach ($products as $product) {
            // Calculate harga_diskon if diskon_persen is set
            if ($product['diskon_persen']) {
                $product['harga_diskon'] = $product['harga'] - ($product['harga'] * $product['diskon_persen'] / 100);
            }
            Product::create($product);
        }
    }
}

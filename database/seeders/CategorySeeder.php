<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Oli Motor',
                'deskripsi' => 'Berbagai jenis oli motor berkualitas tinggi untuk semua tipe motor',
            ],
            [
                'nama' => 'Lampu',
                'deskripsi' => 'Lampu depan, belakang, sein dan aksesoris lampu motor',
            ],
            [
                'nama' => 'Suspensi',
                'deskripsi' => 'Shockbreaker dan komponen suspensi untuk kenyamanan berkendara',
            ],
            [
                'nama' => 'Ban',
                'deskripsi' => 'Ban motor berbagai ukuran dan merek terpercaya',
            ],
            [
                'nama' => 'Velg',
                'deskripsi' => 'Velg racing dan original untuk berbagai tipe motor',
            ],
            [
                'nama' => 'Filter',
                'deskripsi' => 'Filter udara, oli dan bahan bakar untuk performa optimal',
            ],
            [
                'nama' => 'Coolant',
                'deskripsi' => 'Cairan pendingin mesin motor',
            ],
            [
                'nama' => 'Rem',
                'deskripsi' => 'Kampas rem, cakram rem dan komponen sistem pengereman',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

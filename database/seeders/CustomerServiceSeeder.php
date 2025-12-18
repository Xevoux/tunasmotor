<?php

namespace Database\Seeders;

use App\Models\CustomerService;
use Illuminate\Database\Seeder;

class CustomerServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerServices = [
            [
                'nama' => 'Customer Service',
                'nomor_whatsapp' => '081234567890',
                'is_active' => true,
                'urutan' => 1,
            ],
        ];

        foreach ($customerServices as $cs) {
            CustomerService::updateOrCreate(
                ['nomor_whatsapp' => $cs['nomor_whatsapp']],
                $cs
            );
        }
    }
}


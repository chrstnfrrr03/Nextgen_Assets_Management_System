<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::insert([

            [
                'part_no' => 'CCTV-001',
                'brand' => 'Hikvision',
                'part_name' => 'CCTV Camera',
                'description' => 'Outdoor surveillance camera',
                'category_id' => 1,
                'status' => 'available',
                'quantity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'part_no' => 'WEB-001',
                'brand' => 'Hostinger',
                'part_name' => 'Hosting Plan',
                'description' => 'Business hosting',
                'category_id' => 2,
                'status' => 'available',
                'quantity' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
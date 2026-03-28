<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'code' => 'PRD-001',
                'brand' => 'Toyota',
                'name' => 'Engine Oil 5W-30',
                'description' => 'High‑performance synthetic engine oil',
            ],
            [
                'code' => 'PRD-002',
                'brand' => 'Honda',
                'name' => 'Air Filter',
                'description' => 'OEM‑grade air filter for Honda vehicles',
            ],
            [
                'code' => 'PRD-003',
                'brand' => 'Nissan',
                'name' => 'Brake Pads',
                'description' => 'Front brake pads – long‑life ceramic',
            ],
        ]);
    }
}

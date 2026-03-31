<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([

            ['name' => 'CCTV Solutions', 'description' => 'Surveillance and monitoring systems', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Web & Domain Hosting', 'description' => 'Website and domain services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IT Support', 'description' => 'Technical support services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Networking Equipment', 'description' => 'Routers, switches and network devices', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Security Systems', 'description' => 'Access control and security solutions', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Storage Solutions', 'description' => 'Data storage devices and systems', 'created_at' => now(), 'updated_at' => now()],

        ]);
    }
}
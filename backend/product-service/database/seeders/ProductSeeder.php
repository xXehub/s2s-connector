<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create(['name' => 'Laptop ASUS ROG', 'price' => 18500000, 'stock' => 12]);
        Product::create(['name' => 'Monitor LG 27"', 'price' => 3200000, 'stock' => 20]);
        Product::create(['name' => 'Keyboard Mechanical', 'price' => 850000, 'stock' => 45]);
        Product::create(['name' => 'Mouse Wireless Logitech', 'price' => 400000, 'stock' => 35]);
        Product::create(['name' => 'Headset Razer', 'price' => 1200000, 'stock' => 18]);

        // Product::factory(5)->create(); 
    }
}
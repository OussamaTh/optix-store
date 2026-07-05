<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nova',
                'description' => 'Engineered for precision, comfort, and maximum clarity.',
                'price' => 149.00,
                'image_path' => 'images/product_nova.png',
            ],
            [
                'name' => 'Horizon',
                'description' => 'High-performance lenses built to refine every detail.',
                'price' => 169.00,
                'image_path' => 'images/product_horizon.png',
            ],
            [
                'name' => 'Ember',
                'description' => 'Subtle amber lenses that bring warmth to every setting.',
                'price' => 159.00,
                'image_path' => 'images/product_ember.png',
            ],
            [
                'name' => 'Viper',
                'description' => 'Sharp silhouettes for those who move with intention.',
                'price' => 189.00,
                'image_path' => 'images/product_viper.png',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image_path' => $product['image_path'],
                ]
            );
        }
    }
}

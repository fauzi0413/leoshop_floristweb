<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Bucket Uang Koonangan',
                'image' => 'products/bucket1.jpg',
                'description' => 'Rangkaian uang dengan bunga kering premium untuk momen spesial.',
            ],
            [
                'title' => 'Bucket Boneka Wisuda',
                'image' => 'products/bucket2.jpg',
                'description' => 'Boneka lucu dengan dekorasi bunga cantik, cocok untuk hadiah wisuda.',
            ],
            [
                'title' => 'Bucket Snack',
                'image' => 'products/bucket3.jpg',
                'description' => 'Isi penuh snack favorit dibungkus dengan desain modern dan manis.',
            ],
            [
                'title' => 'Bucket Bebek Cone',
                'image' => 'products/bucket4.jpg',
                'description' => 'Desain unik berbentuk cone dengan boneka bebek imut dan pita lembut.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

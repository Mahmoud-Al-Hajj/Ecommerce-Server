<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void{

                Category::factory(5)->create();
                Product::factory(30)->create();
                ProductSize::factory(5)->create();
                ProductImage::factory(30)->create();


    }
}

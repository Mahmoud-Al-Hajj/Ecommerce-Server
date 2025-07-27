<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductSize;
use App\Models\ProductImage;



use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void{

                Product::factory(50)->create();
                ProductCategories::factory(5)->create();
                ProductSize::factory(5)->create();
                ProductImage::factory(50)->create();



    }
}

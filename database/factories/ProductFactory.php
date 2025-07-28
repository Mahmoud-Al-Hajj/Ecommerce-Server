<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('en_US');

            return [
            'name' => $faker->words(3, true),
            'description' => $faker->sentence(),
            'price' => $faker->randomFloat(2, 10, 500),
            'category_id' => Category::inRandomOrder()->value('id'),
            'product_gender' => $faker->randomElement(['Men', 'Women']),
            'quantity' => $faker->numberBetween(0, 100),
            'visible' => $faker->boolean(true),
        ];
    }
}

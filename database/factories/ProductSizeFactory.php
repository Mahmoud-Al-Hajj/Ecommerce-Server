<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductSizeFactory extends Factory
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
    'product_id' => Product::factory(),
    'size' => $this->faker->unique()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
    'stock' => $this->faker->numberBetween(0, 200),
];
    }
}

<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
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
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => $faker->numberBetween(1, 50),
            'price' => $faker->randomFloat(2, 5, 100),
            'item_size' => $faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
        ];
    }
}

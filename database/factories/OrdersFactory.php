<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders>
 */
class OrdersFactory extends Factory
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
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['Pending', 'Paid', 'Packed', 'Shipped']),
            'total_price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}

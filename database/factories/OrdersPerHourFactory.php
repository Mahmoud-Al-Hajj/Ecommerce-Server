<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdersPerHour>
 */
class OrdersPerHourFactory extends Factory
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
            'date' => date('Y-m-d'),
            'time' => $faker->date(),
            'order_count' => $faker->numberBetween(0, 50),
            'revenue' => $faker->randomFloat(2, 100, 10000),
        ];
    }
}

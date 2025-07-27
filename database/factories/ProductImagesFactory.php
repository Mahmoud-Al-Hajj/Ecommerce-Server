<?php

namespace Database\Factories;

use App\Models\ProductCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Images>
 */
class ImagesFactory extends Factory
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
            'category_id' => ProductCategories::factory(),
            'image_url' => $faker->imageUrl(),
            'is_thumbnail' => $faker->boolean(true),
        ];
    }
}

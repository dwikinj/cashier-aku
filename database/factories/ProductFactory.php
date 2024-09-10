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
        return [
            'category_id' => Category::factory(),
            'code' => $this->faker->unique()->numerify('PRD###'),
            'name' => $this->faker->word,
            'brand' => $this->faker->optional()->company,
            'purchase_price' => $this->faker->randomFloat(2, 10, 100),
            'discount' => $this->faker->numberBetween(0, 30),
            'selling_price' => $this->faker->randomFloat(2, 20, 200),
            'stock' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

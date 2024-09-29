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
        $purchasePrice = fake()->randomFloat(2, 0, 3000000);
        $sellingPrice = fake()->randomFloat(2, $purchasePrice, 3000000);
    
        return [
            'category_id' => Category::factory(),
            'code' => fake()->unique()->numerify('PRD###'),
            'name' => fake()->word,
            'brand' => fake()->optional()->company,
            'purchase_price' => $purchasePrice,
            'discount' => fake()->numberBetween(0, 100),
            'selling_price' => $sellingPrice,
            'stock' => fake()->numberBetween(1, 1000),
        ];
    }
}

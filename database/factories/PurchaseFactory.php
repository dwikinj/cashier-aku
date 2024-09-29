<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->first()->id, // Ambil supplier_id secara random dari tabel suppliers
            'total_items' => fake()->numberBetween(1, 100),  // Jumlah item antara 1 hingga 100
            'total_price' => fake()->randomFloat(2, 1000, 1000000), // Harga antara 1.000 sampai 1.000.000
            'discount' => fake()->numberBetween(0, 50), // Diskon antara 0% hingga 50%
            'paid' => fake()->randomFloat(2, 1000, 1000000), // Jumlah yang dibayar antara 1.000 sampai 1.000.000
        ];
    }
}

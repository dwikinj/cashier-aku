<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseDetail>
 */
class PurchaseDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 20);
        $purchase_price = fake()->randomFloat(2, 1000, 100000); // Harga antara 1.000 sampai 100.000
        $subtotal = $quantity * $purchase_price;

        return [
            'purchase_id' => Purchase::inRandomOrder()->first()->id, // Ambil purchase_id secara random dari tabel purchases
            'product_id' => Product::inRandomOrder()->first()->id,  // Ambil product_id secara random dari tabel products
            'purchase_price' => $purchase_price,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }
}

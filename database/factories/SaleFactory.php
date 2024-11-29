<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total_items = fake()->numberBetween(1, 100);
        $total_price = fake()->randomFloat(2, 1000, 1000000); // Harga total antara 1.000 sampai 1.000.000
        $discount = fake()->numberBetween(0, 50); // Diskon antara 0% hingga 50%
        $paid = $total_price - ($total_price * $discount / 100);
        $received = $paid + fake()->randomFloat(2, 0, 500); // Uang yang diterima bisa lebih dari yang dibayar

        return [
            'member_id' => Member::inRandomOrder()->first()->id, // Ambil member_id secara random dari tabel members
            'total_items' => $total_items,
            'total_price' => $total_price,
            'discount' => $discount,
            'paid' => $paid,
            'received' => $received,
            'user_id' => User::inRandomOrder()->first()->id, // Ambil user_id secara random dari tabel users
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => 'Cashier Aku',
            'company_address' => 'Jl. Kiayi Haji Dahlan',
            'company_phone' => '+6289999999998',
            'member_discount' => 5,
            'logo_path' => 'storage/default/company_logo.png',
            'member_card_path' => 'storage/default/card_member.png',
        ];
    }
}

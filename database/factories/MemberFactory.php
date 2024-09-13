<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        return [
            'code' => $this->faker->unique()->numerify('MBR###'),
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => now(),
        ];
    }
}

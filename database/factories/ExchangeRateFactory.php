<?php

namespace Database\Factories;

use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExchangeRate>
 */
class ExchangeRateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'currency' => $this->faker->randomElement(['EUR', 'USD']),
            'source' => $this->faker->randomElement(['oficial', 'paralelo']),
            'value' => $this->faker->randomFloat(4, 30, 60),
            'last_update' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        $purchasePrice = $this->faker->randomFloat(2, 10, 100);
        $sellingPrice = $purchasePrice * $this->faker->randomFloat(2, 1.2, 2.0);

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}

<?php

namespace Database\Factories;

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
    public function definition()
    {
        // $price = '$' . $this->faker->randomFloat();
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->realText(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'category_id' => $this->faker->numberBetween(1, 20),
            'tag_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}

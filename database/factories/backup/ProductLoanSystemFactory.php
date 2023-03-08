<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductLoanSystemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product' => $this->faker->numberBetween(9, 18),
            'user' => $this->faker->numberBetween(2, 11),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'category_id' => $this->faker->numberBetween(1, 20),
            'tag_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}

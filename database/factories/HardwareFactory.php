<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hardware>
 */
class HardwareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'owner_id' => 1,
            'product_name' => $this->faker->company,
            'product_content' => $this->faker->paragraph,
            'product_excerpt' => $this->faker->text,
            'price' => '100.00',
            'created' => $this->faker->dateTime,
            'modified' => $this->faker->dateTime,
            'product_SKU' => '100',
            'product_isLoan' => 0,
            'cover_image' => $this->faker->imageUrl(),
        ];
    }
}

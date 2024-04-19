<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Software>
 */
class SoftwareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => 1,
            'post_title' => $this->faker->sentence,
            'post_content' => $this->faker->paragraph,
            'post_excerpt' => $this->faker->text,
            'created_at' => $this->faker->dateTime,
            'post_modified' => $this->faker->dateTime,
            'post_status' => 'Published',
            'cover_image' => $this->faker->imageUrl(),
        ];
    }
}

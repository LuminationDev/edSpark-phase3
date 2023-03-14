<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
            'event_title' => $this->faker->text,
            'event_content' => $this->faker->paragraph,
            'event_excerpt' => $this->faker->sentence,
            'start_Date' => $this->faker->dateTime,
            'end_date' => $this->faker->dateTime,
            'event_status' => 'Active',
            'cover_image' => $this->faker->imageUrl(),
        ];
    }
}

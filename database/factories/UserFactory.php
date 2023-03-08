<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'display_name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'status' => $this->faker->word,
            'role_id' => 3,
        ];
    }
}

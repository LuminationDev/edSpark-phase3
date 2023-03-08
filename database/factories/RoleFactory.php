<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return void
     */
    public function definition()
    {
        return [
            'role_uid' => $this->faker->uuid,
            'role_name' => $this->faker->word,
            'role_value' => $this->faker->text
        ];
    }
}

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
            'role_id' => 1,
            'permission_id' => 1,
            'site_id' => 1,
            'type_id' => 1
        ];

        // return [
        //     'full_name' => $this->faker->name,
        //     'display_name' => $this->faker->userName,
        //     'email' => $this->faker->unique()->safeEmail,
        //     'password' => ,
        //     'status' => ,
        //     'role_id' => ,
        //     'permission_id' => ,
        //     'site_id' => ,
        //     'type_id' =>
        // ];

        // DB::table('users')->insert([
        //     'full_name' => 'Asim Thapa',
        //     'display_name' => 'Asim',
        //     'email' => 'athapa@lumination.com.au',
        //     'password' => 'edspark12',
        //     'status' => 'Active',
        //     'role_id' => 1,
        //     'permission_id' => 1,
        //     'site_id' => 1,
        //     'type_id' => 1
        // ]);

        // DB::table('users')->insert([
        //     'full_name' => 'Jake Mackinlay',
        //     'display_name' => 'Jake',
        //     'email' => 'jmackinlay@lumination.com.au',
        //     'password' => 'edspark12',
        //     'status' => 'Active',
        //     'role_id' => 1,
        //     'permission_id' => 1,
        //     'site_id' => 1,
        //     'type_id' => 1
        // ]);

        // DB::table('users')->insert([
        //     'full_name' => 'Eric Hartawan',
        //     'display_name' => 'Eric',
        //     'email' => 'ehartawan@lumination.com.au',
        //     'password' => 'edspark12',
        //     'status' => 'Active',
        //     'role_id' => 1,
        //     'permission_id' => 1,
        //     'site_id' => 1,
        //     'type_id' => 1
        // ]);

        // DB::table('users')->insert([
        //     'full_name' => 'Lumination User',
        //     'display_name' => 'Lumination',
        //     'email' => 'luser@lumination.com.au',
        //     'password' => 'edspark12',
        //     'status' => 'Active',
        //     'role_id' => 1,
        //     'permission_id' => 1,
        //     'site_id' => 1,
        //     'type_id' => 1
        // ]);
    }
}

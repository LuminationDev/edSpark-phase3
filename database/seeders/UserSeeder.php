<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
                    [
                        'full_name' => 'Jake Mackinlay',
                        'display_name' => 'JM',
                        'email' => 'jmackinlay@lumination.com.au',
                        'password' => Hash::make('12345678'),
                        'status' => 'Active',
                        'role_id' => 1,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ],
                    [
                        'full_name' => 'Erick Hartawan',
                        'display_name' => 'EH',
                        'email' => 'ehartawan@lumination.com.au',
                        'password' => Hash::make('12345678'),
                        'status' => 'Active',
                        'role_id' => 1,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ],
                    [
                        'full_name' => 'Asim Thapa',
                        'display_name' => 'AT',
                        'email' => 'athapa@lumination.com.au',
                        'password' => Hash::make('12345678'),
                        'status' => 'Active',
                        'role_id' => 1,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ],
                    [
                        'full_name' => 'Lumination User',
                        'display_name' => 'LU',
                        'email' => 'luser@lumination.com.au',
                        'password' => Hash::make('12345678'),
                        'status' => 'Active',
                        'role_id' => 2,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime(),
                    ]
                ];

        // $faker = (array) User::factory(4)->create();
        // // $faker = FakerFactory::create();
        // // dd(collect($faker)->merge($users));
        // // dd(gettype($faker));
        // // Merging Faker data with custom data
        // // $mergedData = array_merge($users, $faker);
        // // dd($mergedData);
        // $collection = collect($faker)->merge($users);

        // Insert data into the database
        User::insert($users);

        // User::factory(5)->create();
    }
}

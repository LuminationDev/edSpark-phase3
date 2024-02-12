<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Models\Site;
use App\Models\Usermeta;

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

        // TODO temporary addition
        Site::insert([
            'site_uid' => '123be4bc-8d62-38cc-93d1-356dfac65178',
            'site_name' => 'Ohio',
            'site_value' => 'DO NOT COMMIT ME',
            'site_id' => 7020
        ]);
        User::insert([
            'full_name' => 'PSM Act, EdSpark Test (Transport Services)',
            'display_name' => 'PS',
            'email' => 'EdSparkTest.PSMAct713@test-schools.sa.edu.au',
            'password' => Hash::make('VDN24tVH23VGmCSt'),
            'status' => 'Active',
            'role_id' => 1,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ]);
        $user_metas = [
            [
                'user_id' => '5',
                'user_meta_key' => 'is_super_admin',
                'user_meta_value' => '1',
            ]
        ];
        // Insert data into the database
        Usermeta::insert($user_metas);
    }
}

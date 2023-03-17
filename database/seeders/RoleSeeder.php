<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role::factory(5)->create();
        $roles = [
            [
                'role_uid' => 'f5802c86-964c-4d0a-8830-da253d9379f1',
                'role_name' => 'Superadmin',
                'role_value' => 'Super admin',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'role_uid' => '005859de-df19-47a9-b118-b25e6273df22',
                'role_name' => 'Administrator',
                'role_value' => 'Able to administrate',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'role_uid' => '9eb6c20a-160b-461b-9233-e11d7f7b0e7d',
                'role_name' => 'Editor',
                'role_value' => 'Able to edit',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'role_uid' => '4ee033ed-d0bc-488d-aae9-91cad5096fea',
                'role_name' => 'Viewer',
                'role_value' => 'Able to view',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'role_uid' => 'dfe592da-3fdf-42e1-a446-25bb83778cc7',
                'role_name' => 'Moderator',
                'role_value' => 'Able to moderate',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
        ];

        Role::insert($roles);
    }
}

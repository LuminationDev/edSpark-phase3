<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            SiteSeeder::class,
            UsertypeSeeder::class,
            UserSeeder::class,
            AdviceSeeder::class,
            AdvicetypeSeeder::class,
            CommunittypeSeeder::class,
            CommunitySeeder::class,
            SoftwareSeeder::class,
            SoftwaretypeSeeder::class,
            EventtypeSeeder::class,
            EventSeeder::class,
            ProductbrandSeeder::class,
            ProductcategorySeeder::class,
            HardwareSeeder::class,
        ]);

    }
}

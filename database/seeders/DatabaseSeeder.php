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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);





        \App\Models\ProductLoanSystem::factory(10)->create();



        // Done
        // \App\Models\Category::factory(10)->create();
        // \App\Models\Tag::factory(10)->create();
        // \App\Models\UserLocation::factory(10)->create();
        // \App\Models\UserRole::factory(10)->create();
        // \App\Models\User::factory(10)->create();
        // \App\Models\Event::factory(10)->create();
        // \App\Models\PostAndArticle::factory(10)->create();
        // \App\Models\Product::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            "user_type" => 'ADMIN',
            'email_verified_at' => now(),
            'password' => '$2y$10$QxxuqiXVkJKBwhvpmAmrlOYGKZexF4mXHeGg1x/Q5jq38nD6L60gm', // password 12345678
            'remember_token' => Str::random(10),
            'address'=> $faker->address,
            'profile_picture'=> $faker->name().".jpg",
            
        ]);

    }
}

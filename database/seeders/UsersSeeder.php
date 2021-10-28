<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $faker = \Faker\Factory::create();
        for ($i=0; $i < 10; $i++) { 
            DB::table("users")->insert([
                "name" => $faker->name(),
                "email" => $faker->safeEmail,
                "password" => '$2y$10$QxxuqiXVkJKBwhvpmAmrlOYGKZexF4mXHeGg1x/Q5jq38nD6L60gm', // password -12345678
                "user_type" => $faker->randomElement(["STUDENT", "TEACHER"]),
                "status" => $faker->randomElement(["ACTIVE", "INACTIVE"]),
                'address'=> $faker->address,
                'profile_picture'=> $faker->name().".jpg",
                
            ]);
        }
    }
}

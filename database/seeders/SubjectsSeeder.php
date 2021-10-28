<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            'name' => 'Hindi',
            'status' => 1,
            'order' => 1,
        ]);
        DB::table('subjects')->insert([
            'name' => 'English',
            'status' => 1,
            'order' => 1,
        ]);
        DB::table('subjects')->insert([
            'name' => 'Physics',
            'status' => 1,
            'order' => 1,
        ]);
        DB::table('subjects')->insert([
            'name' => 'Chemistry',
            'status' => 1,
            'order' => 1,
        ]);
        DB::table('subjects')->insert([
            'name' => 'Social Studies',
            'status' => 1,
            'order' => 1,
        ]);
        
    }
}

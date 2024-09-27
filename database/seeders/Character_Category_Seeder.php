<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Character_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('character_category')->insert([
            'character_category_id' => 1,
            'character_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_category')->insert([
            'character_category_id' => 2,
            'character_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_category')->insert([
            'character_category_id' => 4,
            'character_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_category')->insert([
            'character_category_id' => 4,
            'character_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_category')->insert([
            'character_category_id' => 5,
            'character_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_category')->insert([
            'character_category_id' => 1,
            'character_id' => 3,
            'created_at' => new DateTime(),
        ]);

    }
}

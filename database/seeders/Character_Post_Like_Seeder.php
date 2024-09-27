<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Character_Post_Like_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('character_post_likes')->insert([
            'character_post_id' => 2,
            'user_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_post_likes')->insert([
            'character_post_id' => 2,
            'user_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_post_likes')->insert([
            'character_post_id' => 3,
            'user_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_post_likes')->insert([
            'character_post_id' => 5,
            'user_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_post_likes')->insert([
            'character_post_id' => 1,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_post_likes')->insert([
            'character_post_id' => 4,
            'user_id' => 3,
            'created_at' => new DateTime(),
        ]);
    }
}

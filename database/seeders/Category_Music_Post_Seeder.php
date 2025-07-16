<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Category_Music_Post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 1,
            'music_post_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 3,
            'music_post_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 5,
            'music_post_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 6,
            'music_post_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 2,
            'music_post_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('category_music_post')->insert([
            'music_post_category_id' => 3,
            'music_post_id' => 4,
            'created_at' => new DateTime(),
        ]);
    }
}

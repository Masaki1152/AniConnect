<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Pilgrimage_Post_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 1,
            'anime_pilgrimage_post_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 3,
            'anime_pilgrimage_post_id' => 7,
            'created_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 5,
            'anime_pilgrimage_post_id' => 7,
            'created_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 6,
            'anime_pilgrimage_post_id' => 8,
            'created_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 2,
            'anime_pilgrimage_post_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_category')->insert([
            'pilgrimage_post_category_id' => 3,
            'anime_pilgrimage_post_id' => 6,
            'created_at' => new DateTime(),
        ]);
    }
}

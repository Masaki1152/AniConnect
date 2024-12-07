<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Anime_Pilgrimage_WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 2,
            'anime_pilgrimage_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 2,
            'anime_pilgrimage_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 3,
            'anime_pilgrimage_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 4,
            'anime_pilgrimage_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 5,
            'anime_pilgrimage_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_work')->insert([
            'work_id' => 5,
            'anime_pilgrimage_id' => 5,
            'created_at' => new DateTime(),
        ]);
    }
}

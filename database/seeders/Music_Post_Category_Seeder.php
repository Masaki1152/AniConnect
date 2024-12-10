<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Music_Post_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('music_post_categories')->insert([
            'name' => 'かっこいい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_post_categories')->insert([
            'name' => '元気がもらえる',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_post_categories')->insert([
            'name' => 'しっとり',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_post_categories')->insert([
            'name' => '泣ける',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_post_categories')->insert([
            'name' => '楽しい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_post_categories')->insert([
            'name' => '激しい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

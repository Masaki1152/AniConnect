<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Music_Post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('music_posts')->insert([
            'music_id' => 1,
            'user_id' => 2,
            'work_id' => 1,
            'post_title' => 'ポップな感じ',
            'star_num' => 5,
            'body' => '話の雰囲気と違って明るくていいね',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_posts')->insert([
            'music_id' => 2,
            'user_id' => 1,
            'work_id' => 2,
            'post_title' => 'これぞひぐらし',
            'star_num' => 3,
            'body' => 'いい曲なんだが音程が取りづらい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_posts')->insert([
            'music_id' => 3,
            'user_id' => 4,
            'work_id' => 5,
            'post_title' => 'テンポが最高',
            'star_num' => 4,
            'body' => 'MVが激熱すぎる',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_posts')->insert([
            'music_id' => 1,
            'user_id' => 3,
            'work_id' => 1,
            'post_title' => '話を見た後だと...',
            'star_num' => 5,
            'body' => 'ほむらを思うとねぇ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_posts')->insert([
            'music_id' => 5,
            'user_id' => 6,
            'work_id' => 5,
            'post_title' => 'カラオケの映像が好き',
            'star_num' => 3,
            'body' => 'セイバーたちが酒を酌み交わすシーンが好き',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music_posts')->insert([
            'music_id' => 6,
            'user_id' => 1,
            'work_id' => 3,
            'post_title' => 'これぞラブライブ',
            'star_num' => 5,
            'body' => 'これを超える曲は知らない。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

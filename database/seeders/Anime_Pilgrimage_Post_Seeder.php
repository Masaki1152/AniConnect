<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Anime_Pilgrimage_Post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 2,
            'anime_pilgrimage_id' => 1,
            'post_title' => '雛見沢が見渡せる',
            'scene' => '雛見沢を一望する際の代表的なシーン',
            'body' => '行ってみて感動しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 5,
            'anime_pilgrimage_id' => 5,
            'post_title' => '京都といえば',
            'scene' => 'らきすたの修学旅行回',
            'body' => '久々に清水寺に行きましたが...',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 3,
            'anime_pilgrimage_id' => 4,
            'post_title' => '聖地巡礼！',
            'scene' => 'Aqoursのメンバーが行ってた水族館',
            'body' => '素晴らしいアクアリウムでした',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 6,
            'anime_pilgrimage_id' => 2,
            'post_title' => 'オヤシロ様を感じた',
            'scene' => '古手神社',
            'body' => 'ここが梨花ちゃんの神社なんだなぁ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 1,
            'anime_pilgrimage_id' => 6,
            'post_title' => 'めっちゃ映える橋',
            'scene' => 'コナンの映画',
            'body' => '歌に歌われていましたが実際見るといいですね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimage_posts')->insert([
            'user_id' => 4,
            'anime_pilgrimage_id' => 3,
            'post_title' => 'きっと青春が聞こえる',
            'scene' => 'ミューズのメンバーが良く訪れていた神社',
            'body' => '感無量',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

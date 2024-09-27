<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('characters')->insert([
            'voice_artist_id' => 1,
            'name' => '北条沙都子',
            'wiki_link' => 'https://dic.pixiv.net/a/%E5%8C%97%E6%9D%A1%E6%B2%99%E9%83%BD%E5%AD%90',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'voice_artist_id' => 3,
            'name' => 'フランチェスカ・ルッキーニ',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%83%95%E3%83%A9%E3%83%B3%E3%83%81%E3%82%A7%E3%82%B9%E3%82%AB%E3%83%BB%E3%83%AB%E3%83%83%E3%82%AD%E3%83%BC%E3%83%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'voice_artist_id' => 2,
            'name' => 'リゼ',
            'wiki_link' => 'https://dic.pixiv.net/a/%E5%A4%A9%E3%80%85%E5%BA%A7%E7%90%86%E4%B8%96',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'voice_artist_id' => 4,
            'name' => '美樹さやか',
            'wiki_link' => 'https://dic.pixiv.net/a/%E7%BE%8E%E6%A0%91%E3%81%95%E3%82%84%E3%81%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'voice_artist_id' => 6,
            'name' => 'アルトリア・ペンドラゴン',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%82%A2%E3%83%AB%E3%83%88%E3%83%AA%E3%82%A2%E3%83%BB%E3%83%9A%E3%83%B3%E3%83%89%E3%83%A9%E3%82%B4%E3%83%B3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'voice_artist_id' => 4,
            'name' => 'キュゥべえ',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%82%AD%E3%83%A5%E3%82%A5%E3%81%B9%E3%81%88',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

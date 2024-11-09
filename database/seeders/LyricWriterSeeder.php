<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class LyricWriterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lyric_writers')->insert([
            'name' => '渡辺翔',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%B8%A1%E8%BE%BA%E7%BF%94',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('lyric_writers')->insert([
            'name' => '島みやえい子',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E4%B8%AD%E6%B2%A2%E4%BC%B4%E8%A1%8C',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('lyric_writers')->insert([
            'name' => 'Eir',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E8%97%8D%E4%BA%95%E3%82%A8%E3%82%A4%E3%83%AB',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('lyric_writers')->insert([
            'name' => 'LiSA',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/LiSA',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('lyric_writers')->insert([
            'name' => '畑亜貴',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%95%91%E4%BA%9C%E8%B2%B4',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('lyric_writers')->insert([
            'name' => '石川智晶',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%9F%B3%E5%B7%9D%E6%99%BA%E6%99%B6',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

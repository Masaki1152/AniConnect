<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class VoiceArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('voice_artists')->insert([
            'name' => 'かないみか',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%81%8B%E3%81%AA%E3%81%84%E3%81%BF%E3%81%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('voice_artists')->insert([
            'name' => '種田梨沙',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%A8%AE%E7%94%B0%E6%A2%A8%E6%B2%99',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('voice_artists')->insert([
            'name' => '斎藤千和',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%96%8E%E8%97%A4%E5%8D%83%E5%92%8C',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('voice_artists')->insert([
            'name' => '加藤英美里',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%8A%A0%E8%97%A4%E8%8B%B1%E7%BE%8E%E9%87%8C',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('voice_artists')->insert([
            'name' => '喜多村英梨',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%96%9C%E5%A4%9A%E6%9D%91%E8%8B%B1%E6%A2%A8',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('voice_artists')->insert([
            'name' => '川澄綾子',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%B7%9D%E6%BE%84%E7%B6%BE%E5%AD%90',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

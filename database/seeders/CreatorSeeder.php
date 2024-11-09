<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('creators')->insert([
            'name' => '株式会社スタジオディーン',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%82%B9%E3%82%BF%E3%82%B8%E3%82%AA%E3%83%87%E3%82%A3%E3%83%BC%E3%83%B3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('creators')->insert([
            'name' => '株式会社シャフト',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%82%B7%E3%83%A3%E3%83%95%E3%83%88_(%E3%82%A2%E3%83%8B%E3%83%A1%E5%88%B6%E4%BD%9C%E4%BC%9A%E7%A4%BE)',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('creators')->insert([
            'name' => 'ユーフォーテーブル有限会社',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%83%A6%E3%83%BC%E3%83%95%E3%82%A9%E3%83%BC%E3%83%86%E3%83%BC%E3%83%96%E3%83%AB',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('creators')->insert([
            'name' => 'サンライズ',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%82%B5%E3%83%B3%E3%83%A9%E3%82%A4%E3%82%BA_(%E3%82%A2%E3%83%8B%E3%83%A1%E5%88%B6%E4%BD%9C%E3%83%96%E3%83%A9%E3%83%B3%E3%83%89)',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('creators')->insert([
            'name' => '株式会社京都アニメーション',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E4%BA%AC%E9%83%BD%E3%82%A2%E3%83%8B%E3%83%A1%E3%83%BC%E3%82%B7%E3%83%A7%E3%83%B3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('creators')->insert([
            'name' => '株式会社A-1 Pictures',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/A-1_Pictures',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class ComposerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('composers')->insert([
            'name' => '中沢伴行',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E4%B8%AD%E6%B2%A2%E4%BC%B4%E8%A1%8C',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('composers')->insert([
            'name' => '渡辺翔',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%B8%A1%E8%BE%BA%E7%BF%94',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('composers')->insert([
            'name' => '安田史生',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%AE%89%E7%94%B0%E5%8F%B2%E7%94%9F',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('composers')->insert([
            'name' => '田淵智也',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%94%B0%E6%B7%B5%E6%99%BA%E4%B9%9F',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('composers')->insert([
            'name' => '森慎太郎',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%A3%AE%E6%85%8E%E5%A4%AA%E9%83%8E',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('composers')->insert([
            'name' => '梶浦由記',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%A2%B6%E6%B5%A6%E7%94%B1%E8%A8%98',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class SingerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('singers')->insert([
            'name' => 'Claris',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/ClariS',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('singers')->insert([
            'name' => 'LiSA',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/LiSA',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('singers')->insert([
            'name' => '島みやえい子',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%B3%B6%E3%81%BF%E3%82%84%E3%81%88%E3%81%84%E5%AD%90',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('singers')->insert([
            'name' => '藍井エイル',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E8%97%8D%E4%BA%95%E3%82%A8%E3%82%A4%E3%83%AB',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('singers')->insert([
            'name' => 'μ\'s',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%CE%9C%27s',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('singers')->insert([
            'name' => 'Aqours',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/Aqours',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

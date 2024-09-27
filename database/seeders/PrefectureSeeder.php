<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prefectures')->insert([
            'name' => '東京都',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('prefectures')->insert([
            'name' => '大阪府',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('prefectures')->insert([
            'name' => '京都府',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('prefectures')->insert([
            'name' => '千葉県',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('prefectures')->insert([
            'name' => '岐阜県',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('prefectures')->insert([
            'name' => '静岡県',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

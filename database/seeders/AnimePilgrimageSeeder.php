<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class AnimePilgrimageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 2,
            'prefecture_id' => 5,
            'name' => '城山天守閣 展望台',
            'place' => '〒501-5627 岐阜県大野郡白川村荻町２２６９−１',
            'map_link' => 'https://g.co/kgs/k2a2Nqq',
            'wiki_link' => 'https://shiroten.jp/',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 2,
            'prefecture_id' => 5,
            'name' => '白川八幡神社',
            'place' => '〒501-5627 岐阜県大野郡白川村荻町５５９',
            'map_link' => 'https://g.co/kgs/tLJPcJo',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%99%BD%E5%B7%9D%E5%85%AB%E5%B9%A1%E7%A5%9E%E7%A4%BE',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 3,
            'prefecture_id' => 1,
            'name' => '神田明神',
            'place' => '〒101-0021 東京都千代田区外神田２丁目１６−２',
            'map_link' => 'https://maps.app.goo.gl/E4N7pXELNcYg54b76',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E7%A5%9E%E7%94%B0%E6%98%8E%E7%A5%9E',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 6,
            'prefecture_id' => 6,
            'name' => '伊豆・三津シーパラダイス',
            'place' => '〒410-0295 静岡県沼津市内浦長浜３−１',
            'map_link' => 'https://maps.app.goo.gl/kmJdvHTeLqDA9Nv16',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E4%BC%8A%E8%B1%86%E3%83%BB%E4%B8%89%E6%B4%A5%E3%82%B7%E3%83%BC%E3%83%91%E3%83%A9%E3%83%80%E3%82%A4%E3%82%B9',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 6,
            'prefecture_id' => 3,
            'name' => '清水寺',
            'place' => '〒605-0862 京都府京都市東山区清水１丁目２９４',
            'map_link' => 'https://maps.app.goo.gl/X96prTENpwo3zi6h6',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%B8%85%E6%B0%B4%E5%AF%BA',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('anime_pilgrimages')->insert([
            'work_id' => 6,
            'prefecture_id' => 3,
            'name' => '渡月橋',
            'place' => '〒616-8384 京都府京都市右京区嵯峨天龍寺芒ノ馬場町１−５',
            'map_link' => 'https://maps.app.goo.gl/DyzdAsbxu6VfHnLy5',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E6%B8%A1%E6%9C%88%E6%A9%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

    }
}

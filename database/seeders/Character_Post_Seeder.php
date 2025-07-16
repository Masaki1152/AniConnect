<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Character_Post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('character_posts')->insert([
            'character_id' => 1,
            'user_id' => 1,
            'post_title' => '最高のトラップ使い',
            'star_num' => 5,
            'body' => '可愛すぎましたね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_posts')->insert([
            'character_id' => 4,
            'user_id' => 3,
            'post_title' => 'めっちゃ共感しかない。',
            'star_num' => 4,
            'body' => '展開が展開なだけにかわいそうな扱われ方でした。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_posts')->insert([
            'character_id' => 1,
            'user_id' => 5,
            'post_title' => 'すばらしいの一言',
            'star_num' => 4,
            'body' => 'トラップを使うという素晴らしい能力に感動した。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_posts')->insert([
            'character_id' => 5,
            'user_id' => 5,
            'post_title' => '剣裁きがかっこいい',
            'star_num' => 4,
            'body' => 'ほんとかっこいいですよね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_posts')->insert([
            'character_id' => 6,
            'user_id' => 4,
            'post_title' => '見た目だけ',
            'star_num' => 2,
            'body' => '見た目は可愛いがそれだけ。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_posts')->insert([
            'character_id' => 6,
            'user_id' => 2,
            'post_title' => 'うーん',
            'star_num' => 1,
            'body' => 'エントロピーの話が難しかった。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

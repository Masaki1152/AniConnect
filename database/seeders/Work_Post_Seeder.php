<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class work_post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_posts')->insert([
            'work_id' => 2,
            'user_id' => 1,
            'post_title' => '謎多き村、雛見沢',
            'star_num' => 4,
            'body' => '途中まで見てますけど、謎が多い村ですね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 6,
            'user_id' => 2,
            'post_title' => '旧作のリメイクだと思ってたら…',
            'star_num' => 3,
            'body' => 'なめてました。久々にひぐらし来てます',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 1,
            'user_id' => 3,
            'post_title' => '初見です',
            'star_num' => 5,
            'body' => '今2話まで見ましたけどマミさんって子いいですね～',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 1,
            'user_id' => 4,
            'post_title' => 'ほむらに共感',
            'star_num' => 4,
            'body' => 'いやー全部見ましたけどやばいっすね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 3,
            'user_id' => 5,
            'post_title' => '青春だね',
            'star_num' => 3,
            'body' => 'スクールアイドルってすごいですねぇ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 5,
            'user_id' => 6,
            'post_title' => 'FateといえばZero',
            'star_num' => 4,
            'body' => 'stay nightもいいけどやっぱりZeroがいい。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

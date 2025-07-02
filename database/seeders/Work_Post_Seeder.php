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
            'body' => '途中まで見てますけど、謎が多い村ですね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 6,
            'user_id' => 2,
            'post_title' => '旧作のリメイクだと思ってたら…',
            'body' => 'なめてました。久々にひぐらし来てます',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 1,
            'user_id' => 3,
            'post_title' => '初見です',
            'body' => '今2話まで見ましたけどマミさんって子いいですね～',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 1,
            'user_id' => 4,
            'post_title' => 'ほむらに共感',
            'body' => 'いやー全部見ましたけどやばいっすね。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 3,
            'user_id' => 5,
            'post_title' => '青春だね',
            'body' => 'スクールアイドルってすごいですねぇ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_posts')->insert([
            'work_id' => 5,
            'user_id' => 6,
            'post_title' => 'FateといえばZero',
            'body' => 'stay nightもいいけどやっぱりZeroがいい。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

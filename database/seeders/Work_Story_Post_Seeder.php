<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_Story_Post_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_story_posts')->insert([
            'work_id' => 1,
            'user_id' => 3,
            'sub_title_id' => 3,
            'post_title' => 'マミさぁぁぁん',
            'star_num' => 4,
            'body' => 'いやーひどい脚本や。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_story_posts')->insert([
            'work_id' => 1,
            'user_id' => 5,
            'sub_title_id' => 10,
            'post_title' => 'ついに謎が解けた！',
            'star_num' => 3,
            'body' => 'すがすがしいほどの伏線回収、おつかれした',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_story_posts')->insert([
            'work_id' => 2,
            'user_id' => 3,
            'sub_title_id' => 4,
            'post_title' => '思ってたより平和な世界',
            'star_num' => 5,
            'body' => 'ひぐらしってぐろいと聞いていたけど、そんなことないね',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_story_posts')->insert([
            'work_id' => 5,
            'user_id' => 6,
            'sub_title_id' => 3,
            'post_title' => '難しいね',
            'star_num' => 3,
            'body' => '結局聖杯って何だったのでしょうか。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_story_posts')->insert([
            'work_id' => 3,
            'user_id' => 4,
            'sub_title_id' => 6,
            'post_title' => '癒される',
            'star_num' => 5,
            'body' => 'ついにまきりんぱなが仲間になってうれしい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_story_posts')->insert([
            'work_id' => 4,
            'user_id' => 4,
            'sub_title_id' => 7,
            'post_title' => '結局OPが一番',
            'star_num' => 4,
            'body' => 'ついに大舞台で僕らは今のなかでを披露か。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

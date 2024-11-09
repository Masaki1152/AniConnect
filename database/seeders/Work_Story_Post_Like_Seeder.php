<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_Story_Post_Like_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 1,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 1,
            'user_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 3,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 4,
            'user_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 3,
            'user_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_posts_users')->insert([
            'work_story_post_id' => 6,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
    }
}

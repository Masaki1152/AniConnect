<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_Story_Post_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 1,
            'work_story_post_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 3,
            'work_story_post_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 5,
            'work_story_post_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 6,
            'work_story_post_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 2,
            'work_story_post_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_story_post_category')->insert([
            'work_story_post_category_id' => 3,
            'work_story_post_id' => 4,
            'created_at' => new DateTime(),
        ]);
    }
}

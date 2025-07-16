<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class work_post_work_post_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 3,
            'work_post_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 2,
            'work_post_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 1,
            'work_post_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 4,
            'work_post_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 1,
            'work_post_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('work_post_work_post_category')->insert([
            'work_post_category_id' => 2,
            'work_post_id' => 6,
            'created_at' => new DateTime(),
        ]);
    }
}

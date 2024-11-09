<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class User_Work_Review_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_work_review')->insert([
            'work_review_id' => 1,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('user_work_review')->insert([
            'work_review_id' => 1,
            'user_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('user_work_review')->insert([
            'work_review_id' => 3,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('user_work_review')->insert([
            'work_review_id' => 4,
            'user_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('user_work_review')->insert([
            'work_review_id' => 3,
            'user_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('user_work_review')->insert([
            'work_review_id' => 6,
            'user_id' => 2,
            'created_at' => new DateTime(),
        ]);
    }
}

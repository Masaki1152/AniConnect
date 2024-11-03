<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_Category_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('works_work_categories')->insert([
            'work_category_id' => 3,
            'work_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('works_work_categories')->insert([
            'work_category_id' => 2,
            'work_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('works_work_categories')->insert([
            'work_category_id' => 1,
            'work_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('works_work_categories')->insert([
            'work_category_id' => 4,
            'work_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('works_work_categories')->insert([
            'work_category_id' => 1,
            'work_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('works_work_categories')->insert([
            'work_category_id' => 2,
            'work_id' => 6,
            'created_at' => new DateTime(),
        ]);
    }
}

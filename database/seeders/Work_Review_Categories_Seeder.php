<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_Review_Categories_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_review_categories')->insert([
            'name' => 'かわいい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_review_categories')->insert([
            'name' => '面白い',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_review_categories')->insert([
            'name' => 'ホラー',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_review_categories')->insert([
            'name' => '泣ける',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_review_categories')->insert([
            'name' => '元気がもらえる',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_review_categories')->insert([
            'name' => 'バトル',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

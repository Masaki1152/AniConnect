<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Character_WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('character_work')->insert([
            'work_id' => 1,
            'character_id' => 4,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_work')->insert([
            'work_id' => 2,
            'character_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_work')->insert([
            'work_id' => 6,
            'character_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_work')->insert([
            'work_id' => 5,
            'character_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_work')->insert([
            'work_id' => 1,
            'character_id' => 6,
            'created_at' => new DateTime(),
        ]);
        DB::table('character_work')->insert([
            'work_id' => 3,
            'character_id' => 3,
            'created_at' => new DateTime(),
        ]);
    }
}

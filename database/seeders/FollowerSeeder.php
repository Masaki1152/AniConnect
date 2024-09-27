<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('followers')->insert([
            'following_id' => 1,
            'followed_id' => 3,
            'created_at' => new DateTime(),
        ]);
        DB::table('followers')->insert([
            'following_id' => 2,
            'followed_id' => 5,
            'created_at' => new DateTime(),
        ]);
        DB::table('followers')->insert([
            'following_id' => 6,
            'followed_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('followers')->insert([
            'following_id' => 2,
            'followed_id' => 1,
            'created_at' => new DateTime(),
        ]);
        DB::table('followers')->insert([
            'following_id' => 4,
            'followed_id' => 2,
            'created_at' => new DateTime(),
        ]);
        DB::table('followers')->insert([
            'following_id' => 6,
            'followed_id' => 3,
            'created_at' => new DateTime(),
        ]);

    }
}

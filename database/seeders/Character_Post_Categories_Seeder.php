<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Character_Post_Categories_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('character_post_categories')->insert([
            'name' => 'かわいい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_post_categories')->insert([
            'name' => '短髪',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_post_categories')->insert([
            'name' => '高校生',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_post_categories')->insert([
            'name' => '金髪',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_post_categories')->insert([
            'name' => '元気',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('character_post_categories')->insert([
            'name' => 'おとなしい',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

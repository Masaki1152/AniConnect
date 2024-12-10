<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Pilgrimage_Post_Categories_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pilgrimage_post_categories')->insert([
            'name' => '静か',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_categories')->insert([
            'name' => 'にぎやか',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_categories')->insert([
            'name' => '落ち着く',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_categories')->insert([
            'name' => 'エキサイティング',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_categories')->insert([
            'name' => 'ロマンチック',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('pilgrimage_post_categories')->insert([
            'name' => 'リラックスできる',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Notification_Categories_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notification_categories')->insert([
            'name' => '作品追加',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '登場人物追加',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '音楽追加',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '聖地追加',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '機能追加',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '重要なお知らせ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '注意事項',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => '不具合情報',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notification_categories')->insert([
            'name' => 'その他',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

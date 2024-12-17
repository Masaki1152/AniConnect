<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            'title' => '新しくAniConnectがスタートします。',
            'body' => 'この度、新しくAniConnectが始まりました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notifications')->insert([
            'title' => 'アニメ作品「魔法少女まどか☆マギカ」を追加',
            'body' => 'この度、アニメ作品「魔法少女まどか☆マギカ」を追加しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notifications')->insert([
            'title' => 'アニメ作品「ひぐらしのなく頃に」を追加',
            'body' => 'この度、アニメ作品「ひぐらしのなく頃に」を追加しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notifications')->insert([
            'title' => 'アニメ作品「ラブライブ!（1期）」を追加',
            'body' => 'この度、アニメ作品「ラブライブ!（1期）」を追加しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notifications')->insert([
            'title' => 'アニメ作品「ラブライブ!（2期）」を追加',
            'body' => 'この度、アニメ作品「ラブライブ!（2期）」を追加しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('notifications')->insert([
            'title' => 'アニメ作品「Fate/Zero」を追加',
            'body' => 'この度、アニメ作品「Fate/Zero」を追加しました。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

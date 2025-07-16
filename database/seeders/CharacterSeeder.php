<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class CharacterSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('characters')->insert([
            'work_id' => 2,
            'voice_artist_id' => 1,
            'name' => '北条沙都子',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625948/%E5%8C%97%E6%9D%A1%E6%B2%99%E9%83%BD%E5%AD%90_tuqxye.png',
            'copyright' => '©2020竜騎士07／ひぐらしのなく頃に製作委員会',
            'wiki_link' => 'https://dic.pixiv.net/a/%E5%8C%97%E6%9D%A1%E6%B2%99%E9%83%BD%E5%AD%90',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'work_id' => 6,
            'voice_artist_id' => 3,
            'name' => 'フランチェスカ・ルッキーニ',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625947/%E3%83%AB%E3%83%83%E3%82%AD%E3%83%BC%E3%83%8B_wy9ti4.png',
            'copyright' => '@2010 第501統合戦闘航空団',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%83%95%E3%83%A9%E3%83%B3%E3%83%81%E3%82%A7%E3%82%B9%E3%82%AB%E3%83%BB%E3%83%AB%E3%83%83%E3%82%AD%E3%83%BC%E3%83%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'work_id' => 6,
            'voice_artist_id' => 2,
            'name' => 'リゼ',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625946/%E3%83%AA%E3%82%BC_htn5fz.png',
            'copyright' => '© Koi・芳文社／ご注文はBLOOM製作委員会ですか？',
            'wiki_link' => 'https://dic.pixiv.net/a/%E5%A4%A9%E3%80%85%E5%BA%A7%E7%90%86%E4%B8%96',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'work_id' => 1,
            'voice_artist_id' => 5,
            'name' => '美樹さやか',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625947/%E7%BE%8E%E6%A8%B9%E3%81%95%E3%82%84%E3%81%8B_eff1eq.png',
            'copyright' => '©Magica Quartet／Aniplex・Madoka Partners・MBS',
            'wiki_link' => 'https://dic.pixiv.net/a/%E7%BE%8E%E6%A0%91%E3%81%95%E3%82%84%E3%81%8B',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'work_id' => 5,
            'voice_artist_id' => 6,
            'name' => 'アルトリア・ペンドラゴン',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625946/%E3%82%A2%E3%83%AB%E3%83%88%E3%83%AA%E3%82%A2%E3%83%9A%E3%83%B3%E3%83%89%E3%83%A9%E3%82%B4%E3%83%B3_ycvx6d.png',
            'copyright' => '©Nitroplus／TYPE-MOON・ufotable・FZPC',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%82%A2%E3%83%AB%E3%83%88%E3%83%AA%E3%82%A2%E3%83%BB%E3%83%9A%E3%83%B3%E3%83%89%E3%83%A9%E3%82%B4%E3%83%B3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('characters')->insert([
            'work_id' => 1,
            'voice_artist_id' => 4,
            'name' => 'キュゥべえ',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752625946/%E3%82%AD%E3%83%A5%E3%82%A5%E3%81%B9%E3%81%88_x9yzf9.png',
            'copyright' => '©Magica Quartet／Aniplex・Madoka Partners・MBS',
            'wiki_link' => 'https://dic.pixiv.net/a/%E3%82%AD%E3%83%A5%E3%82%A5%E3%81%B9%E3%81%88',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

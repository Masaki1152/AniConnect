<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('works')->insert([
            'creator_id' => 2,
            'name' => '魔法少女まどか☆マギカ',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752621621/まどマギ_nzgvlq.jpg',
            'copyright' => '©Magica Quartet／Aniplex・Madoka Partners・MBS',
            'term' => '2011年1月7日 - 4月22日',
            'official_site_link' => 'https://10th.madoka-magica.com/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E9%AD%94%E6%B3%95%E5%B0%91%E5%A5%B3%E3%81%BE%E3%81%A9%E3%81%8B%E2%98%86%E3%83%9E%E3%82%AE%E3%82%AB',
            'twitter_link' => 'https://x.com/i/flow/login?redirect_after_login=%2Fhashtag%2F%E9%AD%94%E6%B3%95%E5%B0%91%E5%A5%B3%E3%81%BE%E3%81%A9%E3%81%8B%E3%83%9E%E3%82%AE%E3%82%AB',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('works')->insert([
            'creator_id' => 1,
            'name' => 'ひぐらしのなく頃に',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752622476/%E3%81%B2%E3%81%90%E3%82%89%E3%81%971%E6%9C%9F_b057xk.jpg',
            'copyright' => '©2006竜騎士０７／ひぐらしのなく頃に製作委員会・創通',
            'term' => '2006年4月 - 9月',
            'official_site_link' => 'http://www.oyashirosama.com/web/top/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%81%B2%E3%81%90%E3%82%89%E3%81%97%E3%81%AE%E3%81%AA%E3%81%8F%E9%A0%83%E3%81%AB_(%E3%82%A2%E3%83%8B%E3%83%A1)',
            'twitter_link' => 'https://x.com/higu_anime',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('works')->insert([
            'creator_id' => 4,
            'name' => 'ラブライブ!（1期）',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752622245/%E3%83%A9%E3%83%96%E3%83%A9%E3%82%A4%E3%83%961%E6%9C%9F_rj4wjt.jpg',
            'copyright' => '©2013 プロジェクトラブライブ！',
            'term' => '2013年1月6日 - 3月31日',
            'official_site_link' => 'https://www.lovelive-anime.jp/otonokizaka/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%83%A9%E3%83%96%E3%83%A9%E3%82%A4%E3%83%96!_(%E3%83%86%E3%83%AC%E3%83%93%E3%82%A2%E3%83%8B%E3%83%A1)',
            'twitter_link' => 'https://x.com/LoveLive_staff',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('works')->insert([
            'creator_id' => 4,
            'name' => 'ラブライブ!（2期）',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752622405/%E3%83%A9%E3%83%96%E3%83%A9%E3%82%A4%E3%83%962%E6%9C%9F_obmorx.jpg',
            'copyright' => '©2013 プロジェクトラブライブ！',
            'term' => '2014年4月6日 - 6月29日',
            'official_site_link' => 'https://www.lovelive-anime.jp/otonokizaka/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%83%A9%E3%83%96%E3%83%A9%E3%82%A4%E3%83%96!_(%E3%83%86%E3%83%AC%E3%83%93%E3%82%A2%E3%83%8B%E3%83%A1)',
            'twitter_link' => 'https://x.com/LoveLive_staff',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('works')->insert([
            'creator_id' => 3,
            'name' => 'Fate/Zero',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752622146/FateZero_fqyxky.jpg',
            'copyright' => '©Nitroplus／TYPE-MOON・ufotable・FZPC',
            'term' => '2011年10月 - 12月 2012年4月 - 6月',
            'official_site_link' => 'https://www.fate-zero.jp/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/Fate/Zero',
            'twitter_link' => 'https://x.com/fgoproject',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('works')->insert([
            'creator_id' => 1,
            'name' => 'ひぐらしのなく頃に業',
            'image' => 'https://res.cloudinary.com/dnumegejl/image/upload/v1752621696/%E3%81%B2%E3%81%90%E3%82%89%E3%81%97%E6%A5%AD_b20jft.jpg',
            'copyright' => '©2020竜騎士07／ひぐらしのなく頃に製作委員会',
            'term' => '2020年10月1日 - 2021年3月19日',
            'official_site_link' => 'https://higurashianime.com/',
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%81%B2%E3%81%90%E3%82%89%E3%81%97%E3%81%AE%E3%81%AA%E3%81%8F%E9%A0%83%E3%81%AB_(%E3%82%A2%E3%83%8B%E3%83%A1)',
            'twitter_link' => 'https://x.com/higu_anime',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

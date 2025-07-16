<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class MusicSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('music')->insert([
            'work_id' => 1,
            'singer_id' => 1,
            'lyric_writer_id' => 1,
            'composer_id' => 2,
            'name' => 'コネクト',
            'release_date' => date('2011-2-2'),
            'wiki_link' => 'https://www.youtube-nocookie.com/embed/7EuTPTVpuNI?si=L9E0kdOoOjJeGHNk',
            'youtube_link' => 'https://www.youtube.com/watch?v=7EuTPTVpuNI',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music')->insert([
            'work_id' => 2,
            'singer_id' => 3,
            'lyric_writer_id' => 2,
            'composer_id' => 1,
            'name' => 'ひぐらしのなく頃に',
            'release_date' => date('2006-5-24'),
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E3%81%B2%E3%81%90%E3%82%89%E3%81%97%E3%81%AE%E3%81%AA%E3%81%8F%E9%A0%83%E3%81%AB_(%E6%9B%B2)',
            'youtube_link' => 'https://www.youtube.com/embed/V9nV4SHbcBA?si=AeanCpZ4a7xbcL2a',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music')->insert([
            'work_id' => 5,
            'singer_id' => 2,
            'lyric_writer_id' => 1,
            'composer_id' => 2,
            'name' => 'oath sign',
            'release_date' => date('2011-11-23'),
            'wiki_link' => 'https://ja.wikipedia.org/wiki/Oath_sign',
            'youtube_link' => 'https://www.youtube.com/embed/OcNSFV5Io0Q?si=-vmjrZptm4sHoexx',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music')->insert([
            'work_id' => 6,
            'singer_id' => 2,
            'lyric_writer_id' => 4,
            'composer_id' => 4,
            'name' => 'Catch the Moment',
            'release_date' => date('2017-2-15'),
            'wiki_link' => 'https://ja.wikipedia.org/wiki/Catch_the_Moment',
            'youtube_link' => 'https://www.youtube.com/embed/LJkn2qqtijk?si=IUEueNcdof3CSaFc',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music')->insert([
            'work_id' => 5,
            'singer_id' => 4,
            'lyric_writer_id' => 3,
            'composer_id' => 3,
            'name' => 'MEMORIA',
            'release_date' => date('2011-10-19'),
            'wiki_link' => 'https://ja.wikipedia.org/wiki/MEMORIA_(%E8%97%8D%E4%BA%95%E3%82%A8%E3%82%A4%E3%83%AB%E3%81%AE%E6%9B%B2)',
            'youtube_link' => 'https://www.youtube.com/embed/LnMA-BrpDCg?si=LQMEWiGflChvNIK9',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('music')->insert([
            'work_id' => 3,
            'singer_id' => 5,
            'lyric_writer_id' => 5,
            'composer_id' => 5,
            'name' => '僕らは今のなかで',
            'release_date' => date('2013-1-23'),
            'wiki_link' => 'https://ja.wikipedia.org/wiki/%E5%83%95%E3%82%89%E3%81%AF%E4%BB%8A%E3%81%AE%E3%81%AA%E3%81%8B%E3%81%A7',
            'youtube_link' => 'https://www.youtube.com/embed/KEt6LEPFlHs?si=qNnPUNsi8uSNXRlb',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

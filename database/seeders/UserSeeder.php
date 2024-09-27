<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 25,
            'sex' => '男性',
            'password' => Hash::make('password'),
            'introduction' => 'よろしくお願いします！',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 35,
            'sex' => '男性',
            'password' => Hash::make('password'),
            'introduction' => 'よろしく～',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 15,
            'sex' => '女性',
            'password' => Hash::make('password'),
            'introduction' => 'アニメ好きです。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 28,
            'sex' => '男性',
            'password' => Hash::make('password'),
            'introduction' => 'I love anime.',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 14,
            'sex' => '女性',
            'password' => Hash::make('password'),
            'introduction' => '最高です',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'age' => 52,
            'sex' => '男性',
            'password' => Hash::make('password'),
            'introduction' => '人生はアニメと共に',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PrefectureSeeder::class,
            SingerSeeder::class,
            LyricWriterSeeder::class,
            ComposerSeeder::class,
            VoiceArtistSeeder::class,
            FollowerSeeder::class,
            CharacterSeeder::class,
            MusicSeeder::class,
            AnimePilgrimageSeeder::class,
            CreatorSeeder::class,
            WorkSeeder::class,
            Work_Review_Seeder::class,
            Work_Review_Categories_Seeder::class,
            Work_Review_Category_Seeder::class,
            Work_Review_Like_Seeder::class,
            Work_Categories_Seeder::class,
            Work_Category_Seeder::class,
            Work_Story_Review_Seeder::class,
            Work_Story_Review_Like_Seeder::class,
            Character_Post_Seeder::class,
            Character_Categories_Seeder::class,
            Character_Category_Seeder::class,
            Character_Post_Like_Seeder::class,
            Character_Post_Categories_Seeder::class,
            Character_Post_Category_Seeder::class,
            Music_Post_Seeder::class,
            Music_Post_Like_Seeder::class,
            Anime_Pilgrimage_Post_Seeder::class,
            Anime_Pilgrimage_Post_Like_Seeder::class,
        ]);
    }
}

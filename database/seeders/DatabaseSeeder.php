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
            CreatorSeeder::class,
            WorkSeeder::class,
            MusicSeeder::class,
            AnimePilgrimageSeeder::class,
            CharacterSeeder::class,
            work_Post_Seeder::class,
            work_Post_Categories_Seeder::class,
            work_Post_Work_Post_Category_Seeder::class,
            User_Work_Post_Seeder::class,
            Work_StorySeeder::class,
            Work_Story_Post_Seeder::class,
            Work_Story_Post_Like_Seeder::class,
            Music_Post_Seeder::class,
            Music_Post_Like_Seeder::class,
            Anime_Pilgrimage_Post_Seeder::class,
            Anime_Pilgrimage_Post_Like_Seeder::class,
            Character_Post_Seeder::class,
            Character_Post_Like_Seeder::class,
            Character_Post_Categories_Seeder::class,
            Character_Post_Category_Seeder::class,
            Character_WorkSeeder::class,
            Anime_Pilgrimage_WorkSeeder::class,
            Music_Post_Category_Seeder::class,
            Category_Music_Post_Seeder::class,
            Pilgrimage_Post_Categories_Seeder::class,
            Pilgrimage_Post_Category_Seeder::class,
            Work_Story_Post_Categories_Seeder::class,
            Work_Story_Post_Category_Seeder::class,
            NotificationSeeder::class,
            Notification_Categories_Seeder::class
        ]);
    }
}

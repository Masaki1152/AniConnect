<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\Character;
use App\Models\Music;

class UpdateTopPopularity extends Command
{
    protected $signature = 'popularity:update-top';

    protected $description = 'Update Top Popularity';

    private $work;
    private $character;
    private $music;

    public function __construct(Work $work, Character $character, Music $music)
    {
        parent::__construct();
        $this->work = $work;
        $this->character = $character;
        $this->music = $music;
    }

    public function handle()
    {
        // 作品の上位3つを取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $this->work->updateTopPopularityItems($sufficientReviewsWorks, 'workReviews', 'top_popular_works');

        // あらすじは話数に上限があるため上位3つを取得するためにKernelは使用しない
        // 登場人物の上位3つを取得
        $sufficientPostsCharacters = $this->character->fetchSufficientPostNumCharacters();
        $this->character->updateTopPopularityItems($sufficientPostsCharacters, 'characterPosts', 'top_popular_characters');

        // 音楽の上位3つを取得
        $sufficientPostsMusic = $this->music->fetchSufficientPostNumMusic();
        $this->music->updateTopPopularityItems($sufficientPostsMusic, 'musicPosts', 'top_popular_music');
        $this->info('Popularity scores updated successfully.');
    }
}

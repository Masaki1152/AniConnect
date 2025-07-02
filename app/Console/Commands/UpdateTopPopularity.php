<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\Character;
use App\Models\Music;
use App\Models\AnimePilgrimage;
use Illuminate\Support\Facades\Log;

class UpdateTopPopularity extends Command
{
    protected $signature = 'popularity:update-top';

    protected $description = 'Update Top Popularity';

    private $work;
    private $character;
    private $music;
    private $animePilgrimage;

    public function __construct(Work $work, Character $character, Music $music, AnimePilgrimage $animePilgrimage)
    {
        parent::__construct();
        $this->work = $work;
        $this->character = $character;
        $this->music = $music;
        $this->animePilgrimage = $animePilgrimage;
    }

    public function handle()
    {
        // 作品の上位3つを取得
        $sufficientPostsWorks = $this->work->fetchSufficientPostNumWorks();
        $this->work->updateTopPopularityItems($sufficientPostsWorks, 'workPosts', 'top_popular_works');

        // あらすじは話数に上限があるため上位3つを取得するためにKernelは使用しない
        // 登場人物の上位3つを取得
        $sufficientPostsCharacters = $this->character->fetchSufficientPostNumCharacters();
        $this->character->updateTopPopularityItems($sufficientPostsCharacters, 'characterPosts', 'top_popular_characters');

        // 音楽の上位3つを取得
        $sufficientPostsMusic = $this->music->fetchSufficientPostNumMusic();
        $this->music->updateTopPopularityItems($sufficientPostsMusic, 'musicPosts', 'top_popular_music');

        // 聖地の上位3つを取得
        $sufficientPostsPilgrimages = $this->animePilgrimage->fetchSufficientPostNumPilgrimages();
        $this->animePilgrimage->updateTopPopularityItems($sufficientPostsPilgrimages, 'animePilgrimagePosts', 'top_popular_pilgrimages');

        // 明示的にログを記録
        Log::info('Popularity scores updated successfully.');
        $this->info('Popularity scores updated successfully.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\WorkStory;
use App\Models\Character;
use App\Models\Music;
use App\Models\AnimePilgrimage;
use Illuminate\Support\Facades\Log;

class UpdateAverageStarNum extends Command
{
    protected $signature = 'average-star-num:update';

    protected $description = 'Update Average StarNum';

    private $work;
    private $workStory;
    private $character;
    private $music;
    private $animePilgrimage;

    public function __construct(Work $work, WorkStory $workStory, Character $character, Music $music, AnimePilgrimage $animePilgrimage)
    {
        parent::__construct();
        $this->work = $work;
        $this->workStory = $workStory;
        $this->character = $character;
        $this->music = $music;
        $this->animePilgrimage = $animePilgrimage;
    }

    public function handle()
    {
        // 各作品の平均評価を取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $works = $this->work->all();
        $this->work->updateAverageStarNum($works, $sufficientReviewsWorks, 'workReviews');
        // 各あらすじの平均評価を取得
        $sufficientPostsWorkStories = $this->workStory->fetchSufficientReviewNumWorkStories();
        $workStories = $this->workStory->all();
        $this->workStory->updateAverageStarNum($workStories, $sufficientPostsWorkStories, 'workStoryPosts');
        // 各登場人物の平均評価を取得
        $sufficientPostsCharacters = $this->character->fetchSufficientPostNumCharacters();
        $characters = $this->character->all();
        $this->character->updateAverageStarNum($characters, $sufficientPostsCharacters, 'characterPosts');
        // 各音楽の平均評価を取得
        $sufficientPostsMusic = $this->music->fetchSufficientPostNumMusic();
        $music = $this->music->all();
        $this->music->updateAverageStarNum($music, $sufficientPostsMusic, 'musicPosts');
        // 各聖地の平均評価を取得
        $sufficientPostsPilgrimages = $this->animePilgrimage->fetchSufficientPostNumPilgrimages();
        $animePilgrimages = $this->animePilgrimage->all();
        $this->animePilgrimage->updateAverageStarNum($animePilgrimages, $sufficientPostsPilgrimages, 'animePilgrimagePosts');

        // 明示的にログを記録
        Log::info('Average starNum updated successfully.');
        $this->info('Average starNum updated successfully.');
    }
}

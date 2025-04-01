<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\WorkStory;
use App\Models\Character;

class UpdateTopPopularity extends Command
{
    protected $signature = 'popularity:update-top';

    protected $description = 'Update Top Popularity';

    private $work;
    private $workStory;
    private $character;

    public function __construct(Work $work, WorkStory $workStory, Character $character)
    {
        parent::__construct();
        $this->work = $work;
        $this->workStory = $workStory;
        $this->character = $character;
    }

    public function handle()
    {
        // 作品の上位3つを取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $this->work->updateTopPopularityItems($sufficientReviewsWorks, 'workReviews', 'top_popular_works');

        // あらすじは話数に上限があるため上位3つを取得するためにKernelは使用しない
        // 登場人物の上位3つを取得
        $sufficientReviewsCharacters = $this->character->fetchSufficientReviewNumCharacters();
        $this->character->updateTopPopularityItems($sufficientReviewsCharacters, 'characterPosts', 'top_popular_characters');
        $this->info('Popularity scores updated successfully.');
    }
}

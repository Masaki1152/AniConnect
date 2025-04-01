<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\WorkStory;

class UpdateTopPopularity extends Command
{
    protected $signature = 'popularity:update-top';

    protected $description = 'Update Top Popularity';

    private $work;
    private $workStory;

    public function __construct(Work $work, WorkStory $workStory)
    {
        parent::__construct();
        $this->work = $work;
        $this->workStory = $workStory;
    }

    public function handle()
    {
        // 作品の上位3つを取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $this->work->updateTopPopularityItems($sufficientReviewsWorks, 'workReviews', 'top_popular_works');

        // あらすじは話数に上限があるため上位3つを取得するためにKernelは使用しない
        // $sufficientReviewsWorkStories = $this->work->fetchSufficientReviewNumWorks();
        // $this->workStory->updateTopPopularityItems($sufficientReviewsWorkStories, 'workStoryPosts', 'top_popular_work_stories');
        $this->info('Popularity scores updated successfully.');
    }
}

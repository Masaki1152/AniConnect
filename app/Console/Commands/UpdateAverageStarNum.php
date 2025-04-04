<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\WorkStory;
use Illuminate\Support\Facades\Log;

class UpdateAverageStarNum extends Command
{
    protected $signature = 'average-star-num:update';

    protected $description = 'Update Average StarNum';

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
        // 各作品の平均評価を取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $works = $this->work->all();
        $this->work->updateAverageStarNum($works, $sufficientReviewsWorks, 'workReviews');
        // 各あらすじの平均評価を取得
        $sufficientPostsWorkStories = $this->workStory->fetchSufficientReviewNumWorkStories();
        $workStories = $this->workStory->all();
        $this->workStory->updateAverageStarNum($workStories, $sufficientPostsWorkStories, 'workStoryPosts');

        // 明示的にログを記録
        Log::info('Average starNum updated successfully.');
        $this->info('Average starNum updated successfully.');
    }
}

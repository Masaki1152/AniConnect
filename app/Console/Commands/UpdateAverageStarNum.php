<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use Illuminate\Support\Facades\Log;

class UpdateAverageStarNum extends Command
{
    protected $signature = 'average-star-num:update';

    protected $description = 'Update Average StarNum';

    private $work;

    public function __construct(Work $work)
    {
        parent::__construct();
        $this->work = $work;
    }

    public function handle(Work $work)
    {
        // 各作品の平均評価を取得
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $works = $work->all();
        $this->work->updateAverageStarNum($works, $sufficientReviewsWorks, 'workReviews');

        // 明示的にログを記録
        Log::info('Average starNum updated successfully.');
        $this->info('Average starNum updated successfully.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;

class UpdateTopPopularity extends Command
{
    protected $signature = 'popularity:update-top';

    protected $description = 'Update Top Popularity';

    private $work;

    public function __construct(Work $work)
    {
        parent::__construct();
        $this->work = $work;
    }

    public function handle()
    {
        $sufficientReviewsWorks = $this->work->fetchSufficientReviewNumWorks();
        $this->work->updateTopPopularityItems($sufficientReviewsWorks, 'workReviews', 'top_popular_works');
        $this->info('Popularity scores updated successfully.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;

class UpdateTopCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-top';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update top categories for all works';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Work::all()->each(function ($work) {
            $work->updateTopCategories();
        });

        $this->info('Top categories updated for all works.');
    }
}

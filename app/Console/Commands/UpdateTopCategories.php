<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use App\Models\WorkStory;
use App\Models\Character;
use App\Models\Music;
use App\Models\AnimePilgrimage;
use Illuminate\Support\Facades\Log;

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
        // 作品一覧の上位3カテゴリーを更新
        Work::all()->each(function ($work) {
            $work->updateTopCategories();
        });
        // あらすじ一覧の上位3カテゴリーを更新
        WorkStory::all()->each(function ($workStory) {
            $workStory->updateTopCategories();
        });
        // 登場人物一覧の上位3カテゴリーを更新
        Character::all()->each(function ($character) {
            $character->updateTopCategories();
        });
        // 音楽一覧の上位3カテゴリーを更新
        Music::all()->each(function ($music) {
            $music->updateTopCategories();
        });
        // 聖地一覧の上位3カテゴリーを更新
        AnimePilgrimage::all()->each(function ($pilgrimage) {
            $pilgrimage->updateTopCategories();
        });

        // 明示的にログを記録
        Log::info('Top categories updated for all works.');
        $this->info('Top categories updated for all works.');
    }
}

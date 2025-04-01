<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        Log::info('Schedule method called.');
        $schedule->command('categories:update-top')->daily();
        $schedule->command('popularity:update-top')->daily();

        // 毎日0時にキャッシュを削除
        $schedule->call(function () {
            Cache::forget('top_popular_works');
            Cache::forget('top_popular_characters');
            Cache::forget('top_popular_music');
        })->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\RecruitmentSync::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:recruitment-sync')->withoutOverlapping()->everyMinute()->onFailure(function ($exception) {
            \Log::error('Lỗi xảy ra khi chạy lệnh: ' . $exception->getMessage());
            \Log::error('Stack Trace: ' . $exception->getTraceAsString());
        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }



}

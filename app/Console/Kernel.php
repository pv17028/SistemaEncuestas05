<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UnshareExpiredSurveys::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        Log::info('Scheduling commands');
        $schedule->command('surveys:unshare-expired')->everyMinute();
    }
}
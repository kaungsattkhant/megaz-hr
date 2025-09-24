<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:monthly-opening-and-closing')->monthly();
        $schedule->command('app:prepaid-monthly-schedule')->everyThirtySeconds();
        // $schedule->command('app:asset-depreciation-balance-monthly')->monthlyOn(1, '00:00');
        $schedule->command('app:asset-depreciation-balance-monthly')->everyMinute();
        $schedule->command('app:assign-objectives-to-staffs')->daily('00:00');
        $schedule->command('app:self-check-out')->everyMinute();
        $schedule->command('app:monthly-loan-interest-addition-schedule')->dailyAt('00:10');
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

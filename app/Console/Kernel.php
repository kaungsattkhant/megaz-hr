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
        $schedule->command('app:monthly-ktv-room-charges')->monthly();
        $schedule->command('app:monthly-total-k-t-v-session')->monthly();
        $schedule->command('app:refresh-bar-monthly-sale')->monthly();
        $schedule->command('app:total-ktv-customers')->monthly();
        $schedule->command('app:monthly-total-k-t-v-sales')->monthly();
        $schedule->command('app:ktv-training')->monthly();
        $schedule->command('app:refresh-target-actual-monthly-menu-sale')->dailyAt('00:10');
        $schedule->command('app:record-daily-area-sales-volume-by-staff')->dailyAt('23:59');
        $schedule->command('app:kitchen-monthly-sale')->monthly();
        $schedule->command('app:bar-total-expense')->monthly();
        $schedule->command('app:kitchen-expense')->monthly();
        $schedule->command('app:kitchen-menu-total')->monthly();
        $schedule->command('staff:auto-checkout')->everyMinute();

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

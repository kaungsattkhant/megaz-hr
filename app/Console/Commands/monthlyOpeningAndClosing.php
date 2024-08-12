<?php

namespace App\Console\Commands;

use App\Models\StaffBalance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class monthlyOpeningAndClosing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-opening-and-closing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastMonth = Carbon::now()->subMonth();

        $staffBalances = StaffBalance::whereYear('created_at', $lastMonth->year)
                            ->whereMonth('created_at', $lastMonth->month)
                            ->get();
        foreach ($staffBalances as $staffBalance)
        {
            $currentYear = date('Y');
            $currentMonth = date('m');
            $newStaffBalance =StaffBalance::create([
                'staff_id' => $staffBalance->staff_id,
                'year' => $currentYear,
                'month' => $currentMonth,
                'opening_balance' => $staffBalance->closing_balance,
                'closing_balance' => $staffBalance->closing_balance
            ]);
        }

    }
}

<?php

namespace App\Console\Commands;

use App\Models\CheckIn;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoCheckoutStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staff:auto-checkout';

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
        //
        $now = now();

        // Retrieve all current check-ins
        $checkIns = CheckIn::with('timeShift')
            ->where('is_current_checked_in', 1)
            ->get();

        foreach ($checkIns as $checkIn) {
            $toTime = $checkIn->timeShift->to_time;
            $autoCheckoutTime = now()->setTimeFromTimeString($toTime)->addMinutes(30);
            // If current time is past to_time + 30 minutes
            if ($now->greaterThanOrEqualTo($autoCheckoutTime)) {
                DB::transaction(function () use ($checkIn) {
                    $checkIn->update([
                        'check_out_date_time' => now(),
                        'is_current_checked_in' => 0,
                        'is_self_checkout' => false,
                    ]);
                });

                $this->info("Auto checked out staff ID: {$checkIn->staff_id}");
            }
        }
    }
}

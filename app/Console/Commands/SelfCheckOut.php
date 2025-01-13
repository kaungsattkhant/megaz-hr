<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CheckIn;
use App\Models\TimeShift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SelfCheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:self-check-out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Self-check out for staffs who have not checked out after their shift ends.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        try {

            $checkIns = CheckIn::whereNull('check_out_date_time')->where('is_current_checked_in', true)
                ->whereHas('timeShift', function ($query) {
                    $query->whereRaw('TIMESTAMP(CURDATE(), to_time) < ?', [now()->subHour()]);
                })->get();

            foreach ($checkIns as $checkIn) {
                $toTime = Carbon::parse($checkIn->timeShift->to_time);

                $checkIn->update([
                    'check_out_date_time' => $toTime->format('Y-m-d H:i:s'),
                    'is_current_checked_in' => false,
                    'is_self_checkout' => false,
                ]);
            }

            DB::commit();
            $this->info('Self check-out process executed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to self check out: ' . $e->getMessage());
        }
    }
}

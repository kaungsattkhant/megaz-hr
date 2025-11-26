<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\StaffTimeshift;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Action\SendNotification\FcmSendNotification;

class AlertNotificationNearlyCheckInTime extends Command
{
    use FcmSendNotification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alert-notification-nearly-check-in-time';

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
        DB::beginTransaction();

        try {

            $now = now()->format('Y-m-d H:i');  // compare minute only
            $today = now()->toDateString();

            $staffTimeShifts = StaffTimeshift::where('status', 'confirmed')
                ->whereDate('date_time', $today)
                ->with('timeshift')
                ->get();

            $staffs = [];

            foreach ($staffTimeShifts as $sts) {

                if (!$sts->timeshift) {
                    continue;
                }

                // Combine date + from_time
                $date = Carbon::parse($sts->date_time)->toDateString();
                // $shiftStart = Carbon::parse($date . ' ' . $sts->timeshift->from_time);
                $shiftStart=Carbon::parse($sts->date_time)->setTimeFromTimeString($sts->timeshift->from_time);

                // 10 minutes before shift
                $alertTime = $shiftStart->copy()->subMinutes(10);

                if ($alertTime->format('Y-m-d H:i') == $now) {

                    Log::info("Sending Notification... to staff {$sts->staff_id}");

                    $notiData = [
                        'title' => 'Reminder: Check-In',
                        'preview' => " It's nearly time to check in."
                    ];
                    $this->sendFcmNotification($sts, $sts->staff, $notiData);
                    $staffs[] = $sts->staff;
                }
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error('CheckIn Alert Error: ' . $e->getMessage());
        }

        Log::info('End CheckIn alert! ');
    }
}

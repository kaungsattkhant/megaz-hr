<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Staff;
use App\Enums\StaffStatus;
use App\Http\Action\SendNotification\FcmSendNotification;
use Illuminate\Console\Command;

class NotifyStaffJoinedToday extends Command
{
    use FcmSendNotification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-staff-joined-today';

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
        $today = Carbon::today()->toDateString();

        $staffs = Staff::whereIn('status', [
            StaffStatus::PROBATION->value,
            StaffStatus::PERMANENT->value,
        ])
            ->whereDate('joined_date', $today)
            ->get();

        foreach ($staffs as $staff) {

            $allStaff = Staff::whereIn('status', [
                StaffStatus::PROBATION->value,
                StaffStatus::PERMANENT->value,
            ])
                ->where('id', '!=', $staff->id)
                ->get();

            $joinDateFormatted = Carbon::parse($staff->joined_date)->format('d M, Y');

            $notificationData = [
                'title' => 'New Staff Joined ',
                'preview' => "Staff {$staff->name} has officially joined on {$joinDateFormatted}",
                'type'=> "joined_date"
            ];

            $this->sendFcmNotification($staff, $allStaff, $notificationData);
        }
    }
}

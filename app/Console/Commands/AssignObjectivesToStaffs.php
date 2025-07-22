<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\Objective;
use App\Models\ObjectiveKey;
use App\Models\ObjectiveStaff;
use Illuminate\Console\Command;
use App\Models\ObjectivekeyStaff;
use Illuminate\Support\Facades\Log;

class AssignObjectivesToStaffs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-objectives-to-staffs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Objectives key assigned to relative staffs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $today = now()->format('Y-m-d');
            $objectives = Objective::where('type', 'daily')
                ->get();

            foreach ($objectives as $objective) {
                $roleId = $objective->role_id;

                $staffLists = Staff::staffByRole($roleId);

                foreach ($staffLists as $staff) {

                    ObjectiveStaff::firstOrCreate(
                        [
                            'staff_id' => $staff->id,
                            'objective_id' => $objective->id,
                            'start_date' => $today,
                        ],
                        [
                            'end_date' => 'null',
                            'status' => 'not_started',
                            'okr_point' => $objective->okr_point,
                        ]
                    );
                }
            }

            $this->info('Daily Objective  have been successfully assigned to relevant staff.');
        } catch (\Exception $e) {
            Log::error('Error assigning Objective Keys to staff.', ['message' => $e->getMessage()]);
            $this->error('An error occurred while assigning Objective Keys to staff.');
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\Objective;
use App\Models\ObjectiveKey;
use App\Models\ObjectiveStaff;
use App\Models\ObjectiveAssign;
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
                    $existingAssign = ObjectiveAssign::where('objective_id', $objective->id)
                    ->where('staff_id', $staff->id)
                    ->whereHas('objectiveStaff', function($query) use ($today) {
                        $query->whereDate('start_date', $today);
                    })
                    ->first();
                    if (!$existingAssign) {
                        $objectiveAssign = ObjectiveAssign::create(
                            [
                                'objective_id' => $objective->id,
                                'staff_id' => $staff->id,
                            ]
                        );
                        for ($i = 1; $i <= $objective->repetition; $i++) {
                            ObjectiveStaff::create(
                                [
                                    'objective_assign_id' => $objectiveAssign->id,
                                    'repetition_count' => $i,
                                    'start_date' => $today,
                                    'end_date' => $today,
                                    'status' => 'assigned',
                                    'okr_point' => $objective->okr_point,
                                ]
                            );
                        }
                    }
                }
            }

            $this->info('Daily Objective  have been successfully assigned to relevant staff.');
        } catch (\Exception $e) {
            Log::error('Error assigning Objective Keys to staff.', ['message' => $e->getMessage()]);
            $this->error('An error occurred while assigning Objective Keys to staff.');
        }
    }
}

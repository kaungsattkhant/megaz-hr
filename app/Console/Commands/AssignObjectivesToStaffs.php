<?php

namespace App\Console\Commands;

use App\Models\Objective;
use App\Models\ObjectivekeyStaff;
use App\Models\Staff;
use Illuminate\Console\Command;
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
        $dayName = now()->format('l');

        $objectives = Objective::whereRaw("FIND_IN_SET(?, assigned_days)", [$dayName])->get();

        foreach ($objectives as $objective) {

            $roleId = $objective->role_id;

            $staffLists = Staff::staffByRole($roleId);
            $objectiveKeys = $objective->objectiveKeys;
            foreach ($staffLists as $staff) {

                foreach ($objectiveKeys as $objectiveKey) {
                    ObjectivekeyStaff::firstOrCreate([
                        'staff_id' => $staff->id,
                        'objective_key_id' => $objectiveKey->id,
                    ], [
                        'status' => 'not_started',
                    ]);
                }
            }
        }

        Log::info('Objective Keys assigned to staff for the day: ' . $dayName);
        $this->info('Objective Keys assigned successfully.');
    }
}

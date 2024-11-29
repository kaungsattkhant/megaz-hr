<?php

namespace App\Console\Commands;

use App\Models\Objective;
use App\Models\ObjectiveKey;
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

        try {
            $objectiveKeys = ObjectiveKey::whereRaw("FIND_IN_SET(?, assigned_days)", [$dayName])
                ->get();

            foreach ($objectiveKeys as $objectiveKey) {
                $roleId = $objectiveKey->role_id;

                $staffLists = Staff::staffByRole($roleId);

                foreach ($staffLists as $staff) {

                    ObjectivekeyStaff::firstOrCreate(
                        [
                            'staff_id' => $staff->id,
                            'objective_key_id' => $objectiveKey->id,
                        ],
                        [
                            'status' => 'not_started',
                        ]
                    );
                }
            }

            $this->info('Objective Keys have been successfully assigned to relevant staff.');
        } catch (\Exception $e) {
            Log::error('Error assigning Objective Keys to staff.', ['message' => $e->getMessage()]);
            $this->error('An error occurred while assigning Objective Keys to staff.');
        }
    }
}

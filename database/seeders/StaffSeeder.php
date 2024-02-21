<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
use App\Models\Gender;
use App\Models\Staff;
use App\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $kitchenDept = Department::where('name', 'Kitchen Department')->first();
        $barDept = Department::where('name', 'Bar Department')->first();

        $kitchenSupervisorRole = Role::where('name', 'Supervisor')->where('department_id', $kitchenDept->id)->first();
        $kitchenStaffRole = Role::where('name', 'Staff')->where('department_id', $kitchenDept->id)->first();

        $barSupervisorRole = Role::where('name', 'Supervisor')->where('department_id', $barDept->id)->first();
        $barStaffRole = Role::where('name', 'Staff')->where('department_id', $barDept->id)->first();

        $staff = Staff::create([
            'gender_id' => Gender::where('name', 'Female')->first()->id,
            'department_id' => $kitchenSupervisorRole->department_id,
            'name' => 'Ma Kitchen Supervisor',
            'phone_number' => '09111',
            'password' => 'password',
        ]);

        $staff->roles()->attach($kitchenSupervisorRole);

        $staff = Staff::create([
            'gender_id' => Gender::where('name', 'Male')->first()->id,
            'department_id' => $kitchenStaffRole->department_id,
            'name' => 'Ko Kitchen Staff',
            'phone_number' => '09112',
            'password' => 'password',
        ]);

        $staff->roles()->attach($kitchenStaffRole);

        $staff = Staff::create([
            'gender_id' => Gender::where('name', 'Male')->first()->id,
            'department_id' => $barSupervisorRole->department_id,
            'name' => 'Ko Bar Supervisor',
            'phone_number' => '09221',
            'password' => 'password',
        ]);

        $staff->roles()->attach($barSupervisorRole);

        $staff = Staff::create([
            'gender_id' => Gender::where('name', 'Female')->first()->id,
            'department_id' => $barStaffRole->department_id,
            'name' => 'Ma Bar Staff',
            'phone_number' => '09222',
            'password' => 'password',
        ]);

        $staff->roles()->attach($barStaffRole);
    }
}

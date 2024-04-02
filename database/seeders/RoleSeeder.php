<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $hrDept = Department::where('name', 'HR')->first();
        $financeDept = Department::where('name', 'Finance')->first();
        $adminDept = Department::where('name', 'Admin')->first();
        $managementDept = Department::where('name', 'Management')->first();
        // $barDept = Department::where('name', 'Bar Department')->first();

        Role::create([
            'department_id' => $hrDept->id,
            'name' => 'Staff'
        ]);

        Role::create([
            'department_id' => $managementDept->id,
            'name' => 'Manager'
        ]);

        Role::create([
            'department_id' => $financeDept->id,
            'name' => 'Financial'
        ]);

        Role::create([
            'department_id' => $managementDept->id,
            'name' => 'MD'
        ]);

    }
}

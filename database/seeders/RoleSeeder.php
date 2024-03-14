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
        $operationDept = Department::where('name', 'Operation Department')->first();
        $finaceDepartment = Department::where('name', 'Finance Department')->first();
        // $barDept = Department::where('name', 'Bar Department')->first();

        Role::create([
            'department_id' => $operationDept->id,
            'name' => 'Staff'
        ]);

        Role::create([
            'department_id' => $operationDept->id,
            'name' => 'Manager'
        ]);

        Role::create([
            'department_id' => $finaceDepartment->id,
            'name' => 'Financial'
        ]);

        Role::create([
            'department_id' => $operationDept->id,
            'name' => 'MD'
        ]);

    }
}

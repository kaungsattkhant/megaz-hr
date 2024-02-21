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
        $kitchenDept = Department::where('name', 'Kitchen Department')->first();
        $barDept = Department::where('name', 'Bar Department')->first();

        Role::create([
            'department_id' => $kitchenDept->id,
            'name' => 'Supervisor'
        ]);

        Role::create([
            'department_id' => $barDept->id,
            'name' => 'Supervisor'
        ]);

        Role::create([
            'department_id' => $kitchenDept->id,
            'name' => 'Staff'
        ]);

        Role::create([
            'department_id' => $barDept->id,
            'name' => 'Staff'
        ]);
    }
}

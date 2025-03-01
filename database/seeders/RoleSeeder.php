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
        $cateringDept = Department::where('name', 'Catering')->first();
        // $inventoryDept = Department::where('name', 'Inventory')->first();
        $kitchenDept = Department::where('name', 'Kitchen')->first();
        $barDept = Department::where('name', 'Bar')->first();
        $procurementDept = Department::where('name', 'Procurement')->first();

        $basicRoles = ['Staff', 'Supervisor', 'Manager'];
        $cateringRoles = ['Staff', 'Supervisor', 'Manager', 'Waiter', 'Receptionist', 'Cashier'];

        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $hrDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }

        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $financeDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }

        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $managementDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }

        Role::create([
            'department_id' => $managementDept->id,
            'name' => 'MD',
            'is_available' => 1
        ]);

        foreach ($cateringRoles as $roleName) {
            Role::create([
                'department_id' => $cateringDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }

        // foreach($basicRoles as $roleName){
        //     Role::create([
        //         'department_id' => $inventoryDept->id,
        //         'name' => $roleName
        //     ]);
        // }

        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $kitchenDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }


        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $barDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }
        foreach ($basicRoles as $roleName) {
            Role::create([
                'department_id' => $procurementDept->id,
                'name' => $roleName,
                'is_available' => 1
            ]);
        }

        // Role::create([
        //     'department_id' => $hrDept->id,
        //     'name' => 'Staff'
        // ]);

        // Role::create([
        //     'department_id' => $managementDept->id,
        //     'name' => 'Manager'
        // ]);

        // Role::create([
        //     'department_id' => $financeDept->id,
        //     'name' => 'Financial'
        // ]);

        // Role::create([
        //     'department_id' => $managementDept->id,
        //     'name' => 'MD'
        // ]);

    }
}

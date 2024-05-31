<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = ['HR', 'Finance', 'Admin', 'Management', 'Catering', 'Inventory', 'Kitchen'];
        $hr_features = config('common.hr_features');
        $inventory_features = config('common.inventory_features');
        $finance_features = config('common.finance_features');
        $catering_features = config('common.catering_features');
        foreach ($names as $name) {
            $department = Department::create([
                'name' => $name,
            ]);
            switch ($name) {
                case 'HR':
                    $department->features()->sync($hr_features);
                    break;
                case 'Finance':
                    $department->features()->sync($finance_features);
                    break;
                case 'Inventory':
                    $department->features()->sync($inventory_features);
                case 'Catering':
                    $department->features()->sync($catering_features);
                    break;
                default:
                    $department->features()->sync($hr_features);
            }
            // $department->features()->sync($hr_features);
        }
    }
}

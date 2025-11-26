<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = [
            'HR', 'Finance', 'Admin', 'Management', 'Catering',
            // 'Inventory',
            'Kitchen', 'Bar', 'Procurement', 'Canteen', 'Entertainment'
        ];
        foreach ($names as $name) {
            $department = Department::create([
                'name' => $name,
                'slug' => Str::slug($name, '-')
            ]);
        }
    }
}

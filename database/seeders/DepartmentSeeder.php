<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names=['HR','Finance','Admin','Management', 'Catering', 'Inventory', 'Kitchen'];
        foreach($names as $name){
            Department::create([
                'name' => $name,
            ]);
        }
    }
}

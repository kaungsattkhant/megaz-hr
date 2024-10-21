<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        foreach($departments as $department){
            $inventory = Inventory::create([
                'name' => ($department->name == 'Inventory')?'Main Inventory': $department->name . ' Inventory',
                'is_active' => 1,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
            ]);
            $inventory->inventoryable()->create([
                'inventoryable_type' => 'department',
                'inventoryable_id' => $department->id,
            ]);
        }
    }
}

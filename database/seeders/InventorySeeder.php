<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $names=['Inventory-1'];
        $departMentIds=[1,2,3,4];
        // foreach($names as $name){
            $inventory=\App\Models\Inventory::create([
                'name'=>'Inventory-1'
            ]);
            foreach($departMentIds as $deptId){
                $inventory->inventoryable()->create([
                    'inventoryable_type'=>'department',
                    'inventoryable_id'=>$deptId,
                ]);
            }
        // }
    }
}

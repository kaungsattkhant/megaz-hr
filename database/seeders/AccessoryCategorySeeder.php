<?php

namespace Database\Seeders;

use App\Models\AccessoryCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names=['Accessory Cat-1','Accessory Cat-2','Accessory Cat-3'];
        foreach($names as $name){
            AccessoryCategory::create([
                'name'=>$name,
            ]);
        }
    }
}

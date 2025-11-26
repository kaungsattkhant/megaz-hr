<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AreaType;

use App\Models\AreaCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $typeOne = AreaType::create([
            'name' => 'Bar and Restaurant',
            'type'=> 'bar_and_restaurant',
        ]);

        $cat1 = AreaCategory::create([
            'name' => 'Cooking Area'
        ]);

        Area::create([
            'area_type_id' => $typeOne->id,
            'area_category_id' => $cat1->id,
            'department_id'=>6,
            'name' => 'Cooking Area-1'
        ]);

       
        Area::create([
            'area_type_id' => $typeOne->id,
            'area_category_id' => $cat1->id,
            'department_id'=>6,
            'name' => 'Cooking Area-2'
        ]);

        $typeTwo = AreaType::create([
            'name' => 'KTV',
            'type'=> 'ktv',
        ]);

        $cat2 = AreaCategory::create([
            'name' => 'Selling Area'
        ]);

        $a2=Area::create([
            'area_type_id' => $typeTwo->id,
            'area_category_id' => $cat2->id,
            'department_id'=>5,
            'name' => 'Roof Top(Selling Area-1)'
        ]);
    }
}

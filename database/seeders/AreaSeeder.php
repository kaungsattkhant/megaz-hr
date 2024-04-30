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
            'name' => 'Bar and Restaurant'
        ]);

        $cat1 = AreaCategory::create([
            'name' => 'Cooking Area'
        ]);

        Area::create([
            'area_type_id' => $typeOne->id,
            'area_category_id' => $cat1->id,
            'name' => 'Bar Area'
        ]);
        Area::create([
            'area_type_id' => $typeOne->id,
            'area_category_id' => $cat1->id,
            'name' => 'Kitchen Area'
        ]);

        $typeTwo = AreaType::create([
            'name' => 'KTV'
        ]);

        $cat2 = AreaCategory::create([
            'name' => 'Selling Area'
        ]);

        $a2=Area::create([
            'area_type_id' => $typeTwo->id,
            'area_category_id' => $cat2->id,
            'name' => 'KTV Rooms'
        ]);
    }
}

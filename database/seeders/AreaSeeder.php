<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Area;
use App\Models\AreaType;

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

        Area::create([
            'area_type_id' => $typeOne->id,
            'name' => 'Bar Area'
        ]);
        Area::create([
            'area_type_id' => $typeOne->id,
            'name' => 'Kitchen Area'
        ]);

        $typeTwo = AreaType::create([
            'name' => 'KTV'
        ]);

        Area::create([
            'area_type_id' => $typeTwo->id,
            'name' => 'KTV Rooms'
        ]);
    }
}

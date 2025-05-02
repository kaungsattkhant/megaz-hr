<?php

namespace Database\Seeders;

use App\Models\Uom;
use App\Models\UomConversion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $name = [
            'k',
            'kg',
            'g',
            'L',
            'mL',
            'pcs',
            'pack',
        ];
        foreach ($name as $n) {
            $uom = Uom::create([
                'name' => $n,
                'created_by' => 1,
            ]);
            if ($uom) {
                UomConversion::firstOrCreate(
                    [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                    ],
                    [
                        'base_unit_id' => $uom->id,
                        'conversion_unit_id' => $uom->id,
                        'conversion' => 1,
                        'created_by' => 1,
                        'is_show' => 0
                    ]
                );
            }
        }
    }
}

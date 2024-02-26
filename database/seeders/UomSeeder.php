<?php

namespace Database\Seeders;

use App\Models\Uom;
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
        $name=[
            'k',
            'kg',
            'g',
            'L',
            'mL',
            'pcs',
            'pack',
        ];
        foreach ($name as $n) {
            Uom::create(['name' => $n]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $name=[
            'Inventory Food',
            'Inventory Tobacco',
            'Inventory General',
            'Inventory Beverage',
            'Inventory Stationery',
            'Inventory Packing',
            'Inventory Cleaning Supplies',
            'Inventory Hygiene Supplies',
            'Inventory Decoration',
            'Inventory China Ware/Glass Ware',
            'Inventory Utencils',
            'Inventory Silver & Cutlery'
        ];
        foreach($name as $n){
            Category::create([
            'name'=>$n,
            ]);
        }
    }
}

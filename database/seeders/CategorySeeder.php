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
        $name = [
            'COS - Food',
            'COS - Beverage',
            'COS - Banquet/Function',
            'COS - Fuel',
            'COS - Carriage Inwards',
        ];
        foreach ($name as $n) {
            Category::create([
                'name' => $n,
            ]);
        }
    }
}

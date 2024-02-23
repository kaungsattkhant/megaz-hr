<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\MenuCategory;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MenuCategory::create([
            'name' => 'Fast Foods'
        ]);
        MenuCategory::create([
            'name' => 'Breakfast'
        ]);
        MenuCategory::create([
            'name' => 'Dessert'
        ]);
        MenuCategory::create([
            'name' => 'Beverages'
        ]);
    }
}

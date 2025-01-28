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
            'name' => 'Asian',
            'image_url' => '/storage/menuImages//asian.webp',
            'image_path' => 'menuImages//asian.webp',
        ]);
        MenuCategory::create([
            'name' => 'Appetiser',
            'image_url' => '/storage/menuImages//appitiser.webp',
            'image_path' => 'menuImages//appitiser.webp',
        ]);
        MenuCategory::create([
            'name' => 'Lunch',
            'image_url' => '/storage/menuImages//lunch.webp',
            'image_path' => 'menuImages//lunch.webp',
        ]);
        MenuCategory::create([
            'name' => 'Food',
            'image_url' => '/storage/menuImages//food.webp',
            'image_path' => 'menuImages//food.webp',
        ]);

        MenuCategory::create([
            'name' => 'Beverage',
            'image_url' => '/storage/menuImages//beverage.webp',
            'image_path' => 'menuImages//beverage.webp',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AreaCategory;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Asian',
                'image_url' => '/storage/menuImages/asian.webp',
                'image_path' => 'menuImages/asian.webp',
            ],
            [
                'name' => 'Appetiser',
                'image_url' => '/storage/menuImages/appitiser.webp',
                'image_path' => 'menuImages/appitiser.webp',
            ],
            [
                'name' => 'Lunch',
                'image_url' => '/storage/menuImages/lunch.webp',
                'image_path' => 'menuImages/lunch.webp',
            ],
            [
                'name' => 'Food',
                'image_url' => '/storage/menuImages/food.webp',
                'image_path' => 'menuImages/food.webp',
            ],
            [
                'name' => 'Beverage',
                'image_url' => '/storage/menuImages/beverage.webp',
                'image_path' => 'menuImages/beverage.webp',
            ],
        ];

        $sellingAreaCategory = AreaCategory::whereRaw(
            'LOWER(REPLACE(name, " ", "")) = ?',
            [strtolower(str_replace(' ', '', 'Selling Area'))]
        )->first();

        $activeAreas = Area::where('is_active', 1)
            ->where('area_category_id', $sellingAreaCategory->id)
            ->pluck('id')
            ->toArray();

        foreach ($categories as $categoryData) {
            $menuCategory = MenuCategory::create($categoryData);
            $menuCategory->areas()->sync($activeAreas);
        }
    }
}

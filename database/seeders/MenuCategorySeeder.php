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
            'image_url' => 'https://tinyurl.com/7sfmnzd6',
            'image_path' => 'https://tinyurl.com/7sfmnzd6',
        ]);
        MenuCategory::create([
            'name' => 'Appetiser',
            'image_url' => 'https://tinyurl.com/58fcd76x',
            'image_path' => 'https://tinyurl.com/58fcd76x',
        ]);
        MenuCategory::create([
            'name' => 'Lunch',
            'image_url' => 'https://tinyurl.com/mrnpc7r3',
            'image_path' => 'https://tinyurl.com/mrnpc7r3',
        ]);
        MenuCategory::create([
            'name' => 'Food',
            'image_url' => 'https://tinyurl.com/3npccyut',
            'image_path' => 'https://tinyurl.com/3npccyut',
        ]);

        MenuCategory::create([
            'name' => 'Beverage',
            'image_url' => 'https://tinyurl.com/3mhzx5ts',
            'image_path' => 'https://tinyurl.com/3mhzx5ts',
        ]);
    }
}

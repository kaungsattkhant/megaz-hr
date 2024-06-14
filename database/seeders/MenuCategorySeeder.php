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
            'name' => 'Fast Foods',
            'image_url' => '/storage/images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
            'image_path' => 'images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
        ]);
        MenuCategory::create([
            'name' => 'Breakfast',
            'image_url' => '/storage/images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
            'image_path' => 'images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
        ]);
        MenuCategory::create([
            'name' => 'Dessert',
            'image_url' => '/storage/images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
            'image_path' => 'images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
        ]);
        MenuCategory::create([
            'name' => 'Beverages',
            'image_url' => '/storage/images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
            'image_path' => 'images/3d01584cbe18d346d3b7e439c58dbb6a.jpeg',
        ]);
    }
}

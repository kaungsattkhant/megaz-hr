<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\ComplaintCategorySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DivisionSeeder;
use Database\Seeders\TownshipSeeder;
use Database\Seeders\FeatureSeeder;
use Database\Seeders\InventorySeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StaffSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // (new ComplaintCategorySeeder())->run();
        // (new DepartmentSeeder())->run();
        // (new RoleSeeder())->run();
        // (new GenderSeeder())->run();
        // (new StaffSeeder())->run();
        // (new ServiceCategorySeeder())->run();
        $this->call([
            DivisionSeeder::class,
            TownshipSeeder::class,
            ComplaintCategorySeeder::class,
            // InventorySeeder::class,
            FeatureSeeder::class,
            DepartmentSeeder::class,
            AreaSeeder::class,
            RoleSeeder::class,
            GenderSeeder::class,
            CategorySeeder::class,
            AccessoryCategorySeeder::class,
            MenuCategorySeeder::class,
            ServiceCategorySeeder::class,
            UomSeeder::class,
            // ItemSeeder::class,
            ItemTypeSeeder::class,
            HeadAccountSeeder::class,
            SubAccountSeeder::class,
            // RoomAndTableSeeder::class,
            InventorySeeder::class,
            StaffSeeder::class,
            GpsSeeder::class
        ]);
    }
}

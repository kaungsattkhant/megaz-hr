<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\AreaSeeder;
use Database\Seeders\ComplaintCategorySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\MenuCategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ServiceCategorySeeder;
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
            AreaSeeder::class,
            ComplaintCategorySeeder::class,
            DepartmentSeeder::class,
            RoleSeeder::class,
            GenderSeeder::class,
            CategorySeeder::class,
            MenuCategorySeeder::class,
            StaffSeeder::class,
            ServiceCategorySeeder::class,
            UomSeeder::class,
            ItemSeeder::class,
            HeadAccountSeeder::class,
            SubAccountSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\ComplaintCategorySeeder;
use Database\Seeders\DepartmentSeeder;
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
        (new ComplaintCategorySeeder())->run();
        (new DepartmentSeeder())->run();
        (new RoleSeeder())->run();
        (new GenderSeeder())->run();
        (new StaffSeeder())->run();
        (new ServiceCategorySeeder())->run();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ComplaintCategory;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ComplaintCategory::create([
            'name' => 'M&E',
        ]);
        ComplaintCategory::create([
            'name' => 'Internet Connection',
        ]);
        ComplaintCategory::create([
            'name' => 'Customer Service',
        ]);
        ComplaintCategory::create([
            'name' => 'Other',
        ]);
    }
}

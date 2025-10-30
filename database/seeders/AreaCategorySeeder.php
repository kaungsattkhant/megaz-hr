<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\AreaType;
use App\Models\AreaCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();

        $data = [
            ['name' => 'Cooking Area', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Selling Area', 'created_at' => $now, 'updated_at' => $now],
        ];

        AreaCategory::insert($data);

        AreaType::insert([
            [
                'name' => 'Bar and Restaurant',
                'type' => 'bar_and_restaurant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KTV',
                'type' => 'ktv',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

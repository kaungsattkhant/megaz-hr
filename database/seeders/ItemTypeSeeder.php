<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = ['Bar Groceries', 'Soft/Cold Drink', 'Liquor', 'Bar Fruit', 'Tobaco', 'Meat & Fish'];
        foreach ($names as $name) {
            ItemType::firstOrCreate(
                [
                    'name' => $name,
                ],
                [
                    'name' => $name,
                ]
            );
        }

    }
}

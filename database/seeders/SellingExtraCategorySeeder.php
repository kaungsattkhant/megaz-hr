<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SellingExtraCategory;

class SellingExtraCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = ['Sugar', 'Ice', 'Salt'];
        foreach($names as $name){
            SellingExtraCategory::create([
                'name' => $name
            ]);
        }
    }
}

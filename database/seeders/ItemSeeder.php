<?php

namespace Database\Seeders;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemPrice;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::beginTransaction();
        try {
        $faker = Faker::create();
        $uoms=Uom::pluck('id')->toArray();
        $amounts=[1000,2000,3000,4000,5000,6000,7000];
        $categories=Category::pluck('id')->take(5)->toArray();
        $numCategories = count($categories);
        $numUoms = count($uoms);
        foreach (range(1, 100) as $index) {
            $randomIndex = $faker->numberBetween(0, count($amounts) - 1);
            $uniqueIdentifier = Str::random(8); // Generate a random 8-character string
            $itemName = $faker->word . '_' . $uniqueIdentifier; // Append the identifier to the name
            $item=Item::create([
                'name'=>$itemName,
                'category_id' => $faker->numberBetween(1, $numCategories),
            ]);
            ItemPrice::create([
                'price'=>$amounts[$randomIndex],
                'item_id'=>$item->id,
                'uom_id'=>$faker->numberBetween(1, $numUoms),
            ]);
            $randomUoms = $faker->randomElements($uoms, $faker->numberBetween(1, $numUoms));
            $item->uoms()->sync($randomUoms);
        }
        DB::commit();
        } catch(\Exception $e){
            DB::rollback();
            ResponseMessage($e->getMessage(),402);
            throw $e;
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Uom;
use App\Models\Item;
use App\Models\Category;
use Faker\Factory as Faker;
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
        $category=Category::pluck('id')->toArray();
        foreach (range(1, 100) as $index) {
            $item=Item::create([
                'name'=>$faker->word,
                'category_id' => $faker->numberBetween(1, count($category)),
            ]);
            $randomUoms = $faker->randomElements($uoms, $faker->numberBetween(1, count($uoms)));
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

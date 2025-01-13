<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomAndTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::beginTransaction();
        try {
            // $faker = Faker::create();
            // $types = ['room', 'table'];
            // $prices = [10000, 15000, 20000, 30000];
            // foreach ($types as $type) {
            //     $names = ['A', 'B', 'C', 'D'];
            //     foreach ($names as $name) {
            //         $entity_name = $type == 'room' ? 'Room-' . $name : 'Table-' . $name;
            //         $randomIndex = $faker->numberBetween(0, count($prices) - 1);
            //         \App\Models\Entity::create([
            //             'area_id' => 3,
            //             'name' => $entity_name,
            //             'price_per_hour' => $prices[$randomIndex],
            //             'entity_type' => $type,
            //             'status' => 'inactive'
            //         ]);
            //     }

            // }
            // DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}

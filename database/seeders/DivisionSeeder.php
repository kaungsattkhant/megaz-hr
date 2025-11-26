<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Division::create([
            "id" => 1,
            "name" => "Ayeyarwady Region"
        ]);
        Division::create([
            "id" => 2,
            "name" => "Bago Region"
        ]);
        Division::create([
            "id" => 3,
            "name" => "Chin State"
        ]);
        Division::create([
            "id" => 4,
            "name" => "Kachin State"
        ]);
        Division::create([
            "id" => 5,
            "name" => "Kayah State"
        ]);
        Division::create([
            "id" => 6,
            "name" => "Kayin State"
        ]);
        Division::create([
            "id" => 7,
            "name" => "Magway Region"
        ]);
        Division::create([
            "id" => 8,
            "name" => "Mandalay Region"
        ]);
        Division::create([
            "id" => 9,
            "name" => "Mon State"
        ]);
        Division::create([
            "id" => 10,
            "name" => "Naypyidaw Union Territory"
        ]);
        Division::create([
            "id" => 11,
            "name" => "Rakhine State"
        ]);
        Division::create([
            "id" => 12,
            "name" => "Sagaing Region"
        ]);
        Division::create([
            "id" => 13,
            "name" => "Shan State"
        ]);
        Division::create([
            "id" => 14,
            "name" => "Tanintharyi Region"
        ]);
        Division::create([
            "id" => 15,
            "name" => "Yangon Region"
        ]);
    }
}

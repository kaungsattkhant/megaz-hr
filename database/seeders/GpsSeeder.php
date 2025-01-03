<?php

namespace Database\Seeders;

use App\Models\Gps;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Gps::create([
            'name' => 'GPS Point',
            'latitude' => 37.7749,
            'longitude' => -122.4194
        ]);
    }
}

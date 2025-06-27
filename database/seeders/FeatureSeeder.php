<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = config('feature.modules');
        $names = config('feature.names');
        $slugs = config('feature.slug');
        $items = [];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('features')->truncate();
        // DB::table('department_feature')->truncate();
        // DB::table('feature_staff')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($modules as $i => $module) {
            $feature = Feature::firstOrCreate(
                [
                    'slug' => $slugs[$i],
                ],
                [
                    'module' => $module,
                    'name' => $names[$i],
                    'slug' => $slugs[$i],
                ]
            );
        }
    }
}

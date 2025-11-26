<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $data = [];
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // $path = app_path('Console/Commands/data/modules.json'); // Full path
        // if (!file_exists(dirname($path))) {
        //     mkdir(dirname($path), 0755, true);
        // }
        $json = File::get(base_path('app/Console/Commands/data/features.json'));
        $modules = json_decode($json);
        DB::beginTransaction();
        try {
            foreach ($modules as $i => $module) {
                // dd($module);

                // $data[] = [
                //     "module" => $module->module,
                //     "name" => $module->name,
                //     "slug" => $module->slug,
                // ];
                $data[] = [
                    'module' => $module->module,
                    'name' => $module->name,
                    'slug' => $module->slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // $feature = Feature::firstOrCreate(
                //     [
                //         'slug' => $slugs[$i],
                //     ],
                //     [
                //         'module' => $module->module,
                //         'name' => $module->name,
                //         'slug' => $module->slug,
                //     ]
                // );
            }
            Feature::insertOrIgnore($data);
            // file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
            DB::commit();
            // ResponseMessage('Feature insert successfully',200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}

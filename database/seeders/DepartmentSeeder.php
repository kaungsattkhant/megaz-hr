<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $names = [
            'HR', 'Finance', 'Admin', 'Management', 'Catering',
            // 'Inventory',
            'Kitchen', 'Bar', 'Procurement', 'Canteen', 'Entertainment'
        ];
        // $hr_features = config('common.hr_features');
        // $inventory_features = config('common.inventory_features');
        // $finance_features = config('common.finance_features');
        // $catering_features = config('common.catering_features');
        $hr_features = config('common.hr_feature_slug');
        $inventory_features = config('common.inventory_feature_slug');
        $finance_features = config('common.finance_feature_slug');
        $catering_features = config('common.catering_feature_slug');
        $entertainment_features = config('common.entertainment_feature_slug');
        $management_features = config('common.management_feature_slug');
        $procurement_features = config('common.procurement_feature_slug');
        foreach ($names as $name) {
            $department = Department::create([
                'name' => $name,
                'slug' => Str::slug($name, '-')
            ]);
            // switch ($name) {
            //     case 'HR':
            //         $featureIds = Feature::whereIn('slug', $hr_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     case 'Finance':
            //         $featureIds = Feature::whereIn('slug', $finance_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     case 'Management':
            //         $featureIds = Feature::whereIn('slug', $management_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     // case 'Inventory':
            //     //     $featureIds = Feature::whereIn('slug', $inventory_features)->pluck('id')->toArray();
            //     //     $department->features()->sync($featureIds);
            //     case 'Catering':
            //         $featureIds = Feature::whereIn('slug', $catering_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     case 'Canteen':
            //         $featureIds = Feature::whereIn('slug', $hr_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     case 'Entertainement':
            //         $featureIds = Feature::whereIn('slug', $entertainment_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     case 'Procurement':
            //         $featureIds = Feature::whereIn('slug', $procurement_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            //         break;
            //     default:
            //         $featureIds = Feature::whereIn('slug', $management_features)->pluck('id')->toArray();
            //         $department->features()->sync($featureIds);
            // }
        }
    }
}

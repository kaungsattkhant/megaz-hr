<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Feature;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $hr_features = config('common.hr_feature_slug');
        $inventory_features = config('common.inventory_feature_slug');
        $finance_features = config('common.finance_feature_slug');
        $catering_features = config('common.catering_feature_slug');
        // $departments = Department::whereIn('slug', ['hr','finance','inventory','catering','procurement','management'])->get();
        $departments = Department::whereIn('slug', ['hr'])->get();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('department_feature')->truncate();
        DB::table('feature_staff')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $hr_features = config('common.hr_feature_slug');
        $inventory_features = config('common.inventory_feature_slug');
        $finance_features = config('common.finance_feature_slug');
        $catering_features = config('common.catering_feature_slug');
        // $entertainment_features = config('common.entertainment_feature_slug');
        $management_features = config('common.management_feature_slug');
        $procurement_features = config('common.procurement_feature_slug');
        foreach ($departments as $department) {

            $name = $department->name;
            $staff = Staff::where('department_id', $department->id)->get();
            switch ($name) {
                case 'HR':
                    $featureIds = Feature::whereIn('slug', $hr_features)->pluck('id')->toArray();
                    break;
                case 'Inventory':
                    $featureIds = Feature::whereIn('slug', $inventory_features)->pluck('id')->toArray();
                    break;
                case 'Finance':
                    $featureIds = Feature::whereIn('slug', $finance_features)->pluck('id')->toArray();
                    break;
                case 'Management':
                    $featureIds = Feature::whereIn('slug', $management_features)->pluck('id')->toArray();
                    break;
                case 'Procurement':
                    $featureIds = Feature::whereIn('slug', $procurement_features)->pluck('id')->toArray();
                    break;
                case 'Catering':
                    $featureIds = Feature::whereIn('slug', $catering_features)->pluck('id')->toArray();
                    break;
            }
            if (in_array($department->name, ['HR','Finance','Inventory','Management','Procurement','Catering'])) {
                $department->features()->sync($featureIds);
                foreach ($staff as $s) {
                    $s->features()->sync($featureIds);
                }
            }
        }
    }
}

<?php


namespace App\Repositories\Feature;

use App\Models\Feature;

class FeatureRepository implements FeatureRepositoryInterface
{
    public function listAllData()
    {
        $feature = Feature::all();
        return $feature;
    }
    public function gtFeatureByDepartment($departmentId){
        $features = Feature::whereHas('departments', function($q) use ($departmentId) {
            $q->where('id', $departmentId);
        })->get()
          ->groupBy('module')  // group by module
          ->map(function ($features, $module) {
              return [
                  'module' => $module,
                  'features' => $features->values(), // reset index
              ];
          })
          ->values(); 
        return $features;
    }
}

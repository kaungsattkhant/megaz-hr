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
}

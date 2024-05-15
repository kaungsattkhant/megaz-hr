<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Repositories\Feature\FeatureRepositoryInterface;
use Illuminate\Http\Request;

class FeatureAPIController extends Controller
{
    //
    protected $featureRepo;

    public function __construct(FeatureRepositoryInterface $featureRepo)
    {
        $this->featureRepo = $featureRepo;
    }

    public function getFeatureData()
    {
        $features = $this->featureRepo->listAllData();
        ResponseData($features);
    }
}

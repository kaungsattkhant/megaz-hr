<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\GpsCreateRequest;
use App\Services\GpsService;
use Illuminate\Http\Request;

class GpsController extends Controller
{
    //
    protected $gpsService;

    // Inject the service into the controller
    public function __construct(GpsService $gpsService)
    {
        $this->gpsService = $gpsService;
    }

    public function updateOrCreateGps(GpsCreateRequest $request){
        $gps=$this->gpsService->updateOrCreateGps($request->validated());
        ResponseData($gps);
    }
}

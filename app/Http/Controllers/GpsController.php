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
    public function getGPS(Request $request)
    {
        $gps = $this->gpsService->getGps($request);

       \ResponseData($gps);
    }

    public function getGPSById(int $gpsId)
    {
        $gps = $this->gpsService->getGpsById($gpsId);
        \ResponseData($gps);
    }
    public function updateOrCreateGps(GpsCreateRequest $request){
        $gps=$this->gpsService->updateOrCreateGps($request->all());
        ResponseData($gps);
    }
}

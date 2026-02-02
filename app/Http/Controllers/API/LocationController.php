<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Location\LocationRepositoryInterface;

class LocationController extends Controller
{
    private LocationRepositoryInterface $locationRepository;
    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }
    public function getAllLocations()
    {
        $data = $this->locationRepository->getAllLocations();
        ResponseData($data);
    }
    public function createLocation(Request $request)
    {
        $data = $this->locationRepository->createLocation($request->all());
        ResponseData($data);
    }

    public function getPlaceByLocationAndFloorId($locationId, $floorId)
    {
        $location = $this->locationRepository->getPlaceByLocationAndFloorId($locationId, $floorId);
        if ($location) {
            ResponseData($location);
        } else {
            ResponseMessage('Location not found', 404);
        }
    }
    public function assignStaffToPlace(Request $request, $placeId)
    {
        $location = $this->locationRepository->assignStaffToPlace($request->all(), $placeId);
    }
    public function getAllPlace()
    {
        $data = $this->locationRepository->getAllPlace();
        ResponseData($data);
    }
    public function addedLocation(Request $request)
    {
        $placeId=$request->place_id;
        $data = $this->locationRepository->assignStaffToPlace($request->all(),$placeId);
        ResponseData($data);
    }
}

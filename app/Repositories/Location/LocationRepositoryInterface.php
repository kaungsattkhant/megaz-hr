<?php

namespace App\Repositories\Location;

use App\Models\Location;

interface LocationRepositoryInterface
{
  public function getAllLocations();
  public function createLocation(array $data);
  public function getPlaceByLocationAndFloorId($locationId, $floorId);
  public function assignStaffToPlace(array $data, $placeId);
  public function getAllPlace();
}

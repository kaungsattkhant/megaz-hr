<?php

namespace App\Repositories\Location;

use App\Models\Floor;
use App\Models\Place;
use App\Models\Location;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class LocationRepository implements LocationRepositoryInterface
{
  public function getAllLocations()
  {
    return Location::with(['floors.places'])->get()->map(function ($location) {
      $location->floors->each(function ($floor) {
        $floor->place_count = $floor->places->count();
      });
      return $location;
    });
  }
  public function getPlaceByLocationAndFloorId($locationId, $floorId)
  {
    return Place::where('floor_id', $floorId)
      ->whereHas('floor', function ($query) use ($locationId) {
        $query->where('location_id', $locationId);
      })->with(['floor', 'staff'])->get();
  }

  public function createLocation(array $data)
  {
    DB::beginTransaction();
    try {

      $location = Location::create($data);
      if (isset($data['floors'])) {
        $floors  = json_decode($data['floors'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for Location.', 400);
        }
        foreach ($floors  as $floorData) {
          $floor = Floor::firstOrCreate([
            'name' => $floorData['floor'],
            'location_id' => $location->id
          ]);
          Place::create([
            'name' => $floorData['place'],
            'floor_id' => $floor->id,
            'staff_id' => $floorData['staff_id'] ?? null
          ]);
        }
      }
      DB::commit();
      ResponseData($location->floors);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function assignStaffToPlace(array $data, $placeId)
  {
    DB::beginTransaction();
    try {
      $existStaff = Place::where('staff_id', $data['staff_id'])
        ->where('id', '!=', $placeId)
        ->exists();
      if ($existStaff) {
        return ResponseMessage('This staff is already assigned to another place.', 400);
      }
      $place = Place::findOrFail($placeId);
      $place->update(['staff_id' => $data['staff_id']]);
      DB::commit();
      ResponseData($place);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getAllPlace()
  {
    return Place::with('floor.location', 'staff')
    ->whereNull('staff_id')
    ->get();
  }
}

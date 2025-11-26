<?php

namespace App\Services;

use App\Models\Gps;
use Illuminate\Support\Facades\DB;

class GpsService
{
    public function getGps($request)
    {
        $gpsQuery = Gps::orderBy('id', 'desc');
        $gps = isset($request['page']) ? $gpsQuery->paginate(config('common.list_count')) : $gpsQuery->get();
        return $gps;
    }

    public function getGpsById($gpsId)
    {
        return Gps::find($gpsId);
    }

    public function updateOrCreateGps(array $data)
    {
        try {
            if (!isset($data['id'])) {
                $data['id'] = null;
            }
            return DB::transaction(function () use ($data) {

                return Gps::updateOrCreate(
                    ['id' => $data['id']],
                    [
                        'latitude'  => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'name'    => $data['name'],
                        'branch_name'    => $data['branch_name'],
                        'updated_at' => now(),
                    ]
                );
            });
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}

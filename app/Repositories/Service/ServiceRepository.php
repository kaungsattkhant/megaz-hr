<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allServices = Service::all();
        $services = Pagination($allServices,$request,'services');
        return $services;
    }

    public function createData(array $data)
    {
        $service = Service::create($data);
        return $service;
    }

    public function updateData(array $data,int $id)
    {
        $service = Service::find($id);
        if($service)
        {
            $data = RemoveNullValues($data);
            $service->update($data);
        }

        return $service;
    }

    public function deleteData(int $id)
    {

        $service= Service::find($id);
        if($service)
        {
            $service->is_available =0;
            $service->save();
            return true;
        }
        return false;

    }
}

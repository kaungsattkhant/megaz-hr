<?php

namespace App\Repositories\Entity;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityRepository implements EntityRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allEntity = Entity::all();
        $entities = Pagination($allEntity,$request,'entities');
        return $entities;
    }

    public function createData(array $data)
    {
        $service = Entity::create($data);
        return $service;
    }

    public function updateData(array $data,int $id)
    {
        $service = Entity::find($id);
        if($service)
        {
            $data = RemoveNullValues($data);
            $service->update($data);
        }

        return $service;
    }

    public function deleteData(int $id)
    {

        $service= Entity::find($id);
        if($service)
        {
            $service->is_available =0;
            $service->save();
            return true;
        }
        return false;

    }
}

<?php

namespace App\Repositories\Entity;

use App\Models\Entity;
use Illuminate\Http\Request;

class EntityRepository implements EntityRepositoryInterface
{
    public function listAllData(Request $request, string $entityType)
    {
        if($request->per_page || $request->page){
            if($entityType == 'all'){
                $totalCount = Entity::where('is_available', 1)->count();
            }
            else{
                $totalCount = Entity::where('is_available', 1)->where('entity_type', $entityType)->count();
            }

            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            if($entityType == 'all'){
                $entities = Entity::where('is_available', 1)->with('service_category')
                ->skip($skip)->take($perPage)->get();
            }
            else{
                $entities = Entity::where('is_available', 1)->where('entity_type', $entityType)
                ->with('service_category')
                ->skip($skip)->take($perPage)->get();
            }
            $paginationData = MakePaginationData($request, $totalCount, 'entities');
            $paginationData['entities'] = $entities;

            return $paginationData;
        }
        else{
            if($entityType == 'all'){
                $entities = Entity::where('is_available', 1)
                ->with('service_category')->get();
            }
            else{
                $entities = Entity::where('is_available', 1)->where('entity_type', $entityType)
                ->with('service_category')->get();
            }

            return $entities;
        }
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

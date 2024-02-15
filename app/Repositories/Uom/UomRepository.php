<?php

namespace App\Repositories\Uom;

use App\Models\Uom;
use Illuminate\Http\Request;

class UomRepository implements UomRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Uom::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $uoms = Uom::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'uoms');
            $paginationData['uoms'] = $uoms;

            return $paginationData;
        }
        else{
            $uoms = Uom::all();

            return $uoms;
        }
    }

    public function createData(array $data)
    {
        $uom = Uom::create($data);
        return $uom;
    }

    public function updateData(array $data,int $id)
    {
        $uom = Uom::find($id);
        if($uom)
        {
           $uom->update($data);
        }
        return $uom;
    }

    public function deleteData(int $id)
    {
        $uom= Uom::find($id);
        if($uom)
        {
            $uom->is_active= 0;
            $uom->save();
            return true;
        }else{
            return false;
        }
    }

}

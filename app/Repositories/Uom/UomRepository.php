<?php

namespace App\Repositories\Uom;

use App\Models\Uom;
use Illuminate\Http\Request;

class UomRepository implements UomRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allUoms = Uom::all();
        $uoms = Pagination($allUoms,$request,'uoms');
        return $uoms;
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

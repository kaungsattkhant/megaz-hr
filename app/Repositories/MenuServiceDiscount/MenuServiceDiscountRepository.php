<?php

namespace App\Repositories\MenuServiceDiscount;

use App\Models\MenuServiceDiscount;
use Illuminate\Http\Request;

class MenuServiceDiscountRepository implements MenuServiceDiscountRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $msd = MenuServiceDiscount::paginate(config('common.list_count'));
        ResponseData($msd);
    }

    public function createData( array $data )
    {
        $data['created_by'] = UserData()->id;
        $msd = MenuServiceDiscount::create($data);
        ResponseData($msd);
    }

    public function editData( int $id, array $data)
    {
        $msd = MenuServiceDiscount::find($id);
        $msd->update($data);
        ResponseData($msd);
    }

    public function deleteData(int $id)
    {
        $msd = MenuServiceDiscount::find($id);
        $msd->delete();
        Responsemessage("Menu Service Discount deleted");
    }

}

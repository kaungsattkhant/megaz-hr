<?php

namespace App\Repositories\Transfer;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferRepository implements TransferRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Transfer::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $transfers = Transfer::skip($skip)->take($perPage)->with('role.department')->get();
            $paginationData = MakePaginationData($request, $totalCount, 'tasks');
            $paginationData['tasks'] = $transfers;

            return $paginationData;
        }
        else{
            $transfers = Transfer::all();

            return $transfers;
        }
    }

    public function createData(array $data)
    {
        $data['status'] = "pending";
        $data['date'] = CurrentTime();
        $transfer = Transfer::create($data);
        return $transfer;
    }

    public function updateData(array $data,int $id)
    {
        $transfer = Transfer::find($id);
        if($transfer)
        {
           $transfer->update($data);
        }
        return $transfer;
    }

    public function deleteData(int $id)
    {
        $transfer= Transfer::find($id);
        if($transfer)
        {
            $transfer->delete();
            return true;
        }else{
            return false;
        }
    }

    public function transferConfirm(int $id)
    {
        $transfer = Transfer::find($id);
        if($transfer)
        {
            $data['confirmed_at'] = currentTime();
            $data['confirmed_by'] = $id;
            $data['status'] = "confirmed";
            $transfer->update($data);
        }
        return $transfer;
    }
}

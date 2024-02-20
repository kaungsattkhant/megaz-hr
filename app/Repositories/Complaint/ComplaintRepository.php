<?php

namespace App\Repositories\Complaint;


use App\Models\Complaint;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Complaint::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $complaints = Complaint::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'complaints');
            $paginationData['complaints'] = $complaints;

            return $paginationData;
        }
        else{
            $complaints = Complaint::all();

            return $complaints;
        }
    }

    public function createData(array $data)
    {
        $complaint = Complaint::create($data);
        return $complaint;
    }

    public function updateData(array $data,int $id)
    {
       $complaint = Complaint::find($id);
       if($complaint){
            $data = RemoveNullValues($data);
            $complaint->update($data);
       }

       return $complaint;
    }

    public function deleteData($id)
    {
        $complaint = Complaint::find($id);
        if($complaint)
        {
            $complaint->delete();
            return true;
        }else{
            return false;
        }
    }

    public function statusChange(string $status,int $id)
    {
        $complaint = Complaint::find($id);
        if($complaint!==null)
        {
            $complaint->status = $status;
            $complaint->save();

        }
        return $complaint;
    }
}

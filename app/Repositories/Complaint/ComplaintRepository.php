<?php

namespace App\Repositories\Complaint;


use App\Models\Complaint;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allComplaints = Complaint::all();
        $complaints = Pagination($allComplaints,$request,'complaint');
        return $complaints;
    }

    public function createData(array $data)
    {
        $data['status'] = 'Not yet';
        $complaint = Complaint::create($data);
        return $complaint;
    }

    public function updateData(array $data,$id)
    {
       $complaint = Complaint::find($id);
       if($complaint)
       {
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
}

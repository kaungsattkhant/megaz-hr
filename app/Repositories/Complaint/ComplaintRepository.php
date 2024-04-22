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
            $complaints = Complaint::orderBy('id', 'desc')
                ->with('complaint_category')
                ->skip($skip)
                ->take($perPage)
                ->get();
            $complaints = MakePaginationData($request, $totalCount, 'complaints', $complaints);
            return $complaints;
        }
        else{
            $complaints = Complaint::orderBy('id', 'desc')->with('complaint_category')->get();
            return $complaints;
        }
    }


    public function listComplaintsByStaff(Request $request, int $staffId)
    {
        if($request->per_page || $request->page){
            $totalCount = Complaint::where('posted_by', $staffId)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $complaints = Complaint::where('posted_by', $staffId)->orderBy('id', 'desc')
            ->skip($skip)->take($perPage)->with('complaint_category')->get();
            $complaints = MakePaginationData($request, $totalCount, 'complaints', $complaints);

            return $complaints;
        }
        else{
            $complaints = Complaint::where('posted_by', $staffId)->with('complaint_category')->orderBy('id', 'desc')->get();

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
        if($complaint !== null)
        {
            $complaint->status = $status;
            $complaint->save();

        }
        return $complaint;
    }
}

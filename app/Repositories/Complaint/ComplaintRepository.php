<?php

namespace App\Repositories\Complaint;


use App\Models\Complaint;
use App\Models\ComplaintResponsible;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Complaint::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
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
        } else {
            $complaints = Complaint::orderBy('id', 'desc')->with('complaint_category')->get();
            return $complaints;
        }
    }


    public function listComplaintsByStaff(Request $request, int $staffId)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Complaint::where('posted_by', $staffId)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $complaints = Complaint::where('posted_by', $staffId)->orderBy('id', 'desc')
                ->skip($skip)->take($perPage)->with('complaint_category')->get();
            $complaints = MakePaginationData($request, $totalCount, 'complaints', $complaints);

            return $complaints;
        } else {
            $complaints = Complaint::where('posted_by', $staffId)->with('complaint_category')->orderBy('id', 'desc')->get();

            return $complaints;
        }
    }

    public function responsiblesStaff()
    {
        $responsibles = ComplaintResponsible::where('staff_id',UserData()->id)->with('staff','complaint')->get();
        ResponseData($responsibles);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['posted_by'] = UserData()->id;
            $complaint = Complaint::create($data);

            if ($complaint) {
                $complaintResponsibles = json_decode($data['complaintResponsibles'], true);
                $complaintCarbonCopies = json_decode($data['complaintCarbonCopies'], true);
                if (!empty($complaintCarbonCopies)) {
                    foreach ($complaintResponsibles as $responsible) {
                        $complaint->complaintResponsibles()->create([
                            'staff_id' => $responsible,
                            'complaint_id' => $complaint->id
                        ]);
                    }
                }

                if(!empty($complaintCarbonCopies)) {
                    foreach ($complaintCarbonCopies as $carbonCopy) {
                        $complaint->complaintCarbonCopies()->create([
                            'staff_id' => $carbonCopy,
                            'complaint_id' => $complaint->id
                        ]);
                    }
                }
            }

            DB::commit();
            ResponseData($complaint);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }


    public function updateData(array $data, int $id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $data = RemoveNullValues($data);
            $complaint->update($data);
        }

        return $complaint;
    }

    public function deleteData($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $complaint->delete();
            return true;
        } else {
            return false;
        }
    }

    public function statusChange(string $status, int $id)
    {
        $complaint = Complaint::find($id);

        if ($complaint !== null) {
            if ($complaint->status == $status) {
                ResponseMessage('Your status is already ' . "$status", 422);
            } else if ($complaint->status == "Done") {
                ResponseMessage("Status is already Done, cannot change", 422);
            }
            $complaint->status = $status;
            $complaint->save();
        }
        return $complaint;
    }
}

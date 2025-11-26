<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Complaint\ComplainStatusRequest;
use App\Http\Requests\Complaint\ComplaintCreateRequest;
use App\Http\Requests\Complaint\ComplaintUpdateRequest;

use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Psy\Readline\Hoa\_Protocol;

class ComplaintAPIController extends Controller
{
    //
    protected $complaintRepo;
    public function __construct(ComplaintRepositoryInterface $complaintRepo)
    {
        $this->complaintRepo = $complaintRepo;
    }

    public function getComplainData(Request $request)
    {
        $complaints = $this->complaintRepo->listAllData($request);

        ResponseData($complaints);
    }

    public function getStaffComplaints(Request $request)
    {
        $staffId = $request->user()->id;
        $complaints = $this->complaintRepo->listComplaintsByStaff($request, $staffId);

        ResponseData($complaints);
    }

    public function createComplain(ComplaintCreateRequest $request)
    {
        $data = $request->all();
        $request->validate([
            'complaintImages.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $data['posted_by'] = $request->user()->id;
        $complaint = $this->complaintRepo->createData($data);
    }

    public function updateComplain(ComplaintUpdateRequest $request,$id)
    {
        $request->validate([
            'complaintImages.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $complaint = $this->complaintRepo->updateData($request->all(),$id);
        if(!$complaint)
        {
            ResponseMessage('No complaint found with given id',404);
        }
        ResponseData($complaint);
    }

    public function deleteComplain($id)
    {
        $complaint = $this->complaintRepo->deleteData($id);
        if($complaint==true)
        {
            ResponseMessage("Complain deleted");
        }else{
            ResponseMessage('Complain not found or some error occur');
        }
    }

    public function complainStatusChange(ComplainStatusRequest $request,$id)
    {
        $complaint = $this->complaintRepo->statusChange($request->status,$id);
        if($complaint!==null)
        {
            ResponseMessage('Complaint status changed');
        }else{
            ResponseMessage('Complaint not found or some error occur');
        }
    }

    public function complaintResponsiblesByStaff()
    {
        $this->complaintRepo->complaintResponsiblesStaff();
    }

    public function complaintCarbonCopiesByStaff()
    {
        $this->complaintRepo->complaintCarbonCopiesStaff();
    }

    public function complaintDetail(int $id)
    {
        $complaint = $this->complaintRepo->complaintDetail($id);
    }

    public function deleteComplaintImage($id)
    {
        $this->complaintRepo->deleteComplaintImage($id);
    }

    public function deleteComplaintResponsible($id)
    {
        $this->complaintRepo->deleteComplaintResponsible($id);
    }

    public function deleteComplaintCarbonCopy($id)
    {
        $this->complaintRepo->deleteComplaintCarbonCopy($id);
    }

}

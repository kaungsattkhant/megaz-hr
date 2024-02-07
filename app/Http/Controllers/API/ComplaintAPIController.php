<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\ComplaintCreateRequest;
use App\Http\Requests\Complaint\ComplaintUpdateRequest;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;

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
        $complaints =$this->complaintRepo->listAllData($request);
        ResponseData($complaints);
    }

    public function createComplain(ComplaintCreateRequest $request)
    {
        $complaint = $this->complaintRepo->createData($request->all());
        ResponseData($complaint);
    }

    public function updateComplain(ComplaintUpdateRequest $request,$id)
    {
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
}

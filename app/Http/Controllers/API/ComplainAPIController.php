<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complain\ComplainCreateRequest;
use App\Http\Requests\Complain\ComplainUpdateRequest;
use App\Repositories\Complain\ComplainRepositoryInterface;
use Illuminate\Http\Request;

class ComplainAPIController extends Controller
{
    //
    protected $complainRepo;
    public function __construct(ComplainRepositoryInterface $complainRepo)
    {
        $this->complainRepo = $complainRepo;
    }

    public function getComplainData()
    {
        $complains =$this->complainRepo->listAllData();
        ResponseData($complains);
    }

    public function createComplain(ComplainCreateRequest $request)
    {
        $complain = $this->complainRepo->createData($request->all());
        ResponseData($complain);
    }

    public function updateComplain(ComplainUpdateRequest $request,$id)
    {
        $complain = $this->complainRepo->updateData($request->all(),$id);
        ResponseData($complain);
    }

    public function deleteComplain($id)
    {
        $complain = $this->complainRepo->deleteData($id);
        if($complain==true)
        {
            ResponseMessage("Complain deleted");
        }else{
            ResponseMessage('Complain not found or some error occur');
        }
    }
}

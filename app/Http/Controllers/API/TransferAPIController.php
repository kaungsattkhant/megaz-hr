<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transfer\CreateTransferRequest;
use App\Repositories\Transfer\TransferRepositoryInterface;
use Illuminate\Http\Request;

class TransferAPIController extends Controller
{
    //
    protected $transferRepo;

    public function __construct(TransferRepositoryInterface $transferRepo)
    {
        $this->transferRepo = $transferRepo;
    }

    public function getTransferData(Request $request)
    {
        $transfers = $this->transferRepo->listAllData($request);
        ResponseData($transfers);
    }

    public function createTransfer(CreateTransferRequest $request)
    {
        $transfer = $this->transferRepo->createData($request->all());
        ResponseData($transfer);
    }

    public function updateTransfer(Request $request,int $id)
    {
        $transfer = $this->transferRepo->updateData($request->all(),$id);
        ResponseData($transfer);
    }

    public function deleteTransfer(int $id)
    {
        $transfer = $this->transferRepo->deleteData($id);
        if($transfer==true)
        {
            ResponseMessage("Transfer deleted");
        }else{
            ResponseMessage('Transfer not found or some error occur');
        }
    }

    public function confirmTransfer(int $id)
    {
        $transfer = $this->transferRepo->transferConfirm($id);
        ResponseData($transfer);
    }


    #api

    public function store(Request $request){
        $transfer = $this->transferRepo->createOrUpdate($request);
        ResponseData($transfer);
    }

    public function index(Request $request){
        $transfer = $this->transferRepo->list($request);
        ResponseData($transfer);
    }

    public function transferConfirmationList(Request $request){
        $data = $this->transferRepo->transferConfirmationList($request);
        ResponseData($data);
    }

    public function confirmTransferItem(Request $request){
        $data = $this->transferRepo->confirmTransferItem($request);
        ResponseData($data);
    }
    #end
}

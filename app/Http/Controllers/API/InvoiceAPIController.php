<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomSession\EndRoomSessionRequest;
use App\Repositories\Invoice\InvoiceRepositoryInterface;

class InvoiceAPIController extends Controller
{
    //
    protected $invoiceRepo;

    public function __construct(InvoiceRepositoryInterface $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    public function startEntity(Request $request)
    {
        $data = $request->all();
        if(isset($data['male'])){
            $data['male'] = (int) $data['male'];
        }
        else{
            $data['male'] = 0;
        }
        if(isset($data['female'])){
            $data['female'] = (int) $data['female'];
        }
        else{
            $data['female'] = 0;
        }
        if(isset($data['child'])){
            $data['child'] = (int) $data['child'];
        }
        else{
            $data['child'] = 0;
        }

        $data['created_by'] = 1;
        $invoice = $this->invoiceRepo->createData($data);

        ResponseData($invoice);
    }

    public function addMoreSessions(Request $request){

        $roomAndSession = $this->invoiceRepo->addSessionDuration($request->all());
        ResponseData($roomAndSession);
    }

    public function changeRoom(Request $request){
        $changeRoom = $this->invoiceRepo->invoiceEntityChange($request->all());
        ResponseData($changeRoom);
    }

    public function endRoom(EndRoomSessionRequest $request)
    {

        $endRoom = $this->invoiceRepo->doneEntityWithInvoice($request->all());
        ResponseData($endRoom);
    }

    public function getInvoiceData(Request $request)
    {
        $invoice = $this->invoiceRepo->listAllData($request);
        ResponseData($invoice);
    }

}

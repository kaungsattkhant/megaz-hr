<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\RoomSession\RoomSessionRepositoryInterface;
use Illuminate\Http\Request;

class InvoiceAPIController extends Controller
{
    //
    protected $invoiceRepo;
    protected $roomSessionRepo;

    public function __construct(InvoiceRepositoryInterface $invoiceRepo, RoomSessionRepositoryInterface $roomSessionRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->roomSessionRepo = $roomSessionRepo;
    }

    public function createInvoiceRoomSession(Request $request)
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
        $data['start_date'] = currentTime();
        $data['invoice_id'] = $invoice->id;
        $roomSession = $this->roomSessionRepo->creaetRoomSession($data);
        ResponseData($roomSession);
    }


}

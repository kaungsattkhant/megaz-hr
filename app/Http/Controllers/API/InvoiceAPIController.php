<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomSession\EndRoomSessionRequest;
use App\Models\Package;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;

class InvoiceAPIController extends Controller
{
    //
    protected $invoiceRepo;
    protected $orderRepo;

    public function __construct(InvoiceRepositoryInterface $invoiceRepo, OrderRepositoryInterface $orderRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->orderRepo = $orderRepo;
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
        if($data['type'] == 'package')
        {
            $package= Package::where('id',$data['package_id'])->first();
            $order['menuArray'] = $package->menuPackages->map(function($menuPackage) {
                return [
                    'menu_id' => $menuPackage->menu_id,
                    'quantity' => $menuPackage->quantity,
                    'original_price' => $menuPackage->menu->prices->first()->price ?? 0,
                    'menu_category_id' => $menuPackage->menu->menu_category_id,
                    'remark' => 'package order',
                ];
            });
            $order['order_type'] = 'package';
            $order['invoice_id'] = $invoice->id;
            $this->orderRepo->createMultipleOrder($order);

        }

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

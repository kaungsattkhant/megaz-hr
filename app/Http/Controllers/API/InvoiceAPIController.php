<?php

namespace App\Http\Controllers\API;

use App\Events\PosRoomDoneNotification;
use App\Events\RoomDoneNotificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomSession\EndRoomSessionRequest;
use App\Models\Department;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Role;
use App\Models\RoomSession;
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
        $data = $request->except('waiter');

        $data['is_waiter'] = ($request->waiter) ? 1:0;

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

    public function endRoom(Request $request)
    {
        $catering_department = Department::where('name','Catering')->first();
        $invoice = Invoice::where('invoice_id',$request->invoice_id)->first();
        $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
        $entity = Entity::find($latestRoomSession->entity_id);

        if(isset($request->waiter))
        {

            $managerRole = Role::whereIn('name', 'Manager')
                        ->where('department_id', $catering_department->id)
                        ->toArray();
            $entity->status = 'done_pending';
            $entity->save();
            broadcast(new PosRoomDoneNotification($entity,$managerRole->id));
            ResponseMessage("The request to quit the room {$entity->name} has been sent. Please wait for the confirmation from the catering department.");

        }else if(isset($request->is_confirm))
        {
            $role = Role::where('name','Staff')->where('department_id', $catering_department->id)->first();

            if($request->is_confirm != 1)
            {
                $msg = "The request to quit the room {$entity->name} has been rejected. Thank you for your understanding.";
                $entity->status = 'active';
                $entity->save();
                broadcast(new RoomDoneNotificationRequest($entity,$msg,$role->id));
                ResponseMessage($msg);

            }else{
                $msg = "The request to quit the room {$entity->name} has been confirmed. The room will be quit and will soon close. Thank you.";
                broadcast(new RoomDoneNotificationRequest($entity,$msg,$role->id));
                ResponseMessage($msg);

            }
        }
        $endRoom = $this->invoiceRepo->doneEntityWithInvoice($request->all());
        ResponseData($endRoom);
    }

    public function getInvoiceData(Request $request)
    {
        $invoice = $this->invoiceRepo->listAllData($request);
        ResponseData($invoice);
    }

    public function doneRoom(Request $request)
    {
        $invoice = $this->invoiceRepo->doneRoom($request->all());
        ResponseData($invoice);
    }

    public function roomConfirm(Request $request)
    {
        $invoice = $this->invoiceRepo->invoiceConfirm($request->all());
    }

}

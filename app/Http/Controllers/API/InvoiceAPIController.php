<?php

namespace App\Http\Controllers\API;

use App\Events\PosRoomDoneNotification;
use App\Events\RoomDoneNotificationRequest;
use App\Events\RoomNotificationRequest;
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
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['is_waiter'] = (($request->waiter) || $request->waiter=="1")  ? 1 : 0;
            if (isset($data['male'])) {
                $data['male'] = (int) $data['male'];
            } else {
                $data['male'] = 0;
            }
            if (isset($data['female'])) {
                $data['female'] = (int) $data['female'];
            } else {
                $data['female'] = 0;
            }
            if (isset($data['child'])) {
                $data['child'] = (int) $data['child'];
            } else {
                $data['child'] = 0;
            }

            $data['entity_id'] = $request->entity_id;
            $returnData = $this->invoiceRepo->createData($data);
            if (isset($data['type']) && $data['type'] == 'package') {
                // $orderData['invoice_id'] = $returnData['invoice']->id;
                // $orderData['menuArray'] = json_decode($request->orders, true);
                // $order = $this->orderRepo->createMultipleOrder($orderData);

                // if (isset($data['is_waiter'])) {
                //     if ($data['is_waiter'] == 1) {
                //         broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], UserData()->department_id,$order['order'], $order['orderItems']));
                //     }
                // }
            }else{
                if (isset($data['is_waiter'])) {
                    if ($data['is_waiter'] == 1) {
                        broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], UserData()->department_id,null, []));
                    }
                }
            }
            DB::commit();
            ResponseData($returnData);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function addMoreSessions(Request $request)
    {

        $roomAndSession = $this->invoiceRepo->addSessionDuration($request->all());
        ResponseData($roomAndSession);
    }

    public function changeRoom(Request $request)
    {
        $changeRoom = $this->invoiceRepo->invoiceEntityChange($request->all());
        ResponseData($changeRoom);
    }

    public function endRoom(Request $request)
    {
        $catering_department = Department::where('name', 'Catering')->first();
        $invoice = Invoice::find($request->invoice_id);

        //end invoice for table
        if($invoice->entity_id!=null){
            $entity=Entity::find($invoice->entity_id);
        }else{
            $activeInvoiceSession=$invoice->activeInvoiceSession;
            if(!$activeInvoiceSession){
                ResponseMessage('Room is invalid',419);
            }
            $entity=$activeInvoiceSession->entity;
            // $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
            // $entity = Entity::find($latestRoomSession->entitySession->entity_id);
        }
        //end invoice for room
        if (isset($request->waiter)) {
            $managerRole = Role::where('name', 'Manager')
                ->where('department_id', $catering_department->id)
                ->first();
            $entity->status = 'done_pending';
            $entity->save();
            broadcast(new PosRoomDoneNotification($invoice, $entity, $managerRole->id));
            ResponseMessage("The request to quit the room {$entity->name} has been sent. Please wait for the confirmation from the catering department.");
        } else if (isset($request->is_confirm)) {
            $role = Role::where('name', 'Staff')->where('department_id', $catering_department->id)->first();
            if ($request->is_confirm != 1) {
                $msg = "The request to quit the room {$entity->name} has been rejected. Thank you for your understanding.";
                $entity->status = 'active';
                $entity->save();
                broadcast(new RoomDoneNotificationRequest($entity, $msg, $role->id));
                ResponseMessage($msg);
            } else {
                $msg = "The request to quit the room {$entity->name} has been confirmed. The room will be quit and will soon close. Thank you.";
                broadcast(new RoomDoneNotificationRequest($entity, $msg, $role->id));
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

    public function addService(Request $request){
        $data=$this->invoiceRepo->addService($request);
        ResponseData($data);
    }

    public function endService(Request $request){
        $data=$this->invoiceRepo->endService($request);
        ResponseData($data);
    }
}

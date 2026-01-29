<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Department;
use App\Models\RoomSession;
use Illuminate\Http\Request;
use App\Models\EntitySession;
use App\Models\InvoiceSession;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\PosRoomDoneNotification;
use App\Events\RoomNotificationRequest;
use App\Http\Requests\InvoicePaidRequest;
use App\Events\RoomDoneNotificationRequest;
use App\Http\Requests\Room\EntityValidationRequest;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Http\Requests\Room\EntityEndValidationRequest;
use App\Http\Requests\Room\EntityStartValidationRequest;
use App\Http\Requests\RoomSession\EndRoomSessionRequest;
use App\Repositories\Invoice\InvoiceRepositoryInterface;
use App\Http\Requests\Room\EntityChangeValidationRequest;
use App\Http\Requests\RoomSession\AddSessionDurationRequest;

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

    public function startEntity(EntityStartValidationRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['is_waiter'] = (($request->waiter) || $request->waiter == "1") ? 1 : 0;
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
            if (isset($data['is_waiter'])) {
                if ($data['is_waiter'] == 1) {
                    //change noti requset from department to receptionist role
                    // broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], UserData()->department_id, null, []));
                    // $getRoleByName='Receptionist';
                    //send not package
                    $receptionistRole = Role::getRoleByName('Receptionist');
                    if (!$receptionistRole) {
                        ResponseMessage('Reception Role Not found', 419);
                    }
                    //currenty comment for broadcast
                    broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], $receptionistRole->id, null, []));
                    // ResponseMessage('Room has been requested to open',200);
                }
            }
            // if (isset($data['type']) && $data['type'] == 'package') {
            //     // $orderData['invoice_id'] = $returnData['invoice']->id;
            //     // $orderData['menuArray'] = json_decode($request->orders, true);
            //     // $order = $this->orderRepo->createMultipleOrder($orderData);
            //     // $receptionistRole = Role::getRoleByName('Receptionist');
            //     // if (isset($data['is_waiter'])) {
            //     //     if ($data['is_waiter'] == 1) {
            //     //         // broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], $receptionistRole->id, null, []));
            //     //         // broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], UserData()->department_id,$order['order'], $order['orderItems']));
            //     //     }
            //     // }
            // } else {
            //     if (isset($data['is_waiter'])) {
            //         if ($data['is_waiter'] == 1) {
            //             //change noti requset from department to receptionist role
            //             // broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], UserData()->department_id, null, []));
            //             // $getRoleByName='Receptionist';
            //             //send not package
            //             $receptionistRole = Role::getRoleByName('Receptionist');
            //             if (!$receptionistRole) {
            //                 ResponseMessage('Reception Role Not found', 419);
            //             }
            //             broadcast(new RoomNotificationRequest($returnData['customer'], $returnData['entity'], $returnData['invoice'], $receptionistRole->id, null, []));
            //         }
            //     }
            // }
            DB::commit();
            ResponseData($returnData);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function addMoreSessions(AddSessionDurationRequest $request)
    {
        // ResponseMessage('Add Sesion is invalid', 419);
        $roomAndSession = $this->invoiceRepo->addSessionDuration($request->all());
        ResponseData($roomAndSession);
    }

    public function changeRoom(EntityChangeValidationRequest $request)
    {
        $changeRoom = $this->invoiceRepo->invoiceEntityChange($request->all());
        ResponseData($changeRoom);
    }

    public function endRoom(EntityValidationRequest $request)
    {
        // $catering_department = Department::where('name', 'Catering')->first();
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($request->invoice_id);
            if (!$invoice) {
                ResponseMessage('Invoice Not Found at End Room', 419);
            }
            //end invoice for table
            if ($request->entity_type == 'table') {
                $entity = Entity::find($invoice->entity_id);
            } else {
                $activeInvoiceSession = $invoice->activeInvoiceSession;
                if (!$activeInvoiceSession) {
                    ResponseMessage('Room is invalid', 419);
                }
                $entity = $activeInvoiceSession->entity;
            }
            //end invoice for room
            $receptionistRole = Role::getRoleByName('Receptionist');
            $waiterRole = Role::getRoleByName('Waiter');
            if (isset($request->waiter)) {
                if (!isset($request->total)) {
                    ResponseMessage('Total Field is required', 422);
                }
                $order = $invoice->order;
                if ($order) {
                    $orderItems = $order->orderItems;
                    if ($orderItems->isNotEmpty()) {
                        $unChooseOrderItemByArea = $orderItems->where('area_id', null)->first();
                        if ($unChooseOrderItemByArea) {
                            ResponseMessage('Area need to conifirm by area', 419);
                        }
                    }
                }
                $entity->status = 'done_pending';
                $entity->save();
                DB::commit();
                broadcast(new PosRoomDoneNotification($invoice, $entity, $receptionistRole->id));
                ResponseMessage("The request to quit the room {$entity->name} has been sent. Please wait for the confirmation from the catering department.");
            }
            // if (isset($request->is_confirm)) {
            //     $this->confirmEndRoom($request, $entity);
            // }
            else if (isset($request->is_confirm)) {
                $msg = '';
                if ($request->is_confirm != 1) {
                    $msg = "The request to quit the room {$entity->name} has been rejected. Thank you for your understanding.";
                    // $entity->status = 'active';
                    // $entity->is_active = 1;
                    // $entity->save();
                    broadcast(new RoomDoneNotificationRequest($entity, $msg, $waiterRole->id));
                    ResponseMessage($msg);
                } else {
                    $msg = "The request to quit the room {$entity->name} has been confirmed. The room will be quit and will soon close. Thank you.";
                    $entity->status = 'inactive';
                    $entity->is_active = 0;
                    $entity->save();
                    broadcast(new RoomDoneNotificationRequest($entity, $msg, $waiterRole->id));
                }
            }
            $endRoom = $this->invoiceRepo->doneEntityWithInvoice($request->all());
            // dd('fail safe');
            DB::commit();
            ResponseData($endRoom);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function confirmEndRoom($request, $entity)
    {
        DB::beginTransaction();
        try {
            $waiterRole = Role::getRoleByName('Waiter');
            if (isset($request->is_confirm)) {
                $msg = '';
                if ($request->is_confirm != 1) {
                    $msg = "The request to quit the room {$entity->name} has been rejected. Thank you for your understanding.";
                    $entity->status = 'active';
                    $entity->is_active = 1;
                    $entity->save();
                    broadcast(new RoomDoneNotificationRequest($entity, $msg, $waiterRole->id));
                    ResponseMessage($msg);
                } else {
                    $msg = "The request to quit the room {$entity->name} has been confirmed. The room will be quit and will soon close. Thank you.";
                    $entity->status = 'inactive';
                    $entity->is_active = 0;
                    $entity->save();
                    broadcast(new RoomDoneNotificationRequest($entity, $msg, $waiterRole->id));
                }
            }
            $endRoom = $this->invoiceRepo->doneEntityWithInvoice($request->all());
            ResponseData($endRoom);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function getInvoiceData(Request $request)
    {
        $invoice = $this->invoiceRepo->listAllData($request);
        ResponseData($invoice);
    }

    public function doneRoom(EntityValidationRequest $request)
    {
        $invoice = $this->invoiceRepo->doneRoom($request->all());
        ResponseData($invoice);
    }

    public function roomConfirm(Request $request)
    {
        $invoice = $this->invoiceRepo->invoiceConfirm($request->all());
    }

    public function addService(Request $request)
    {
        $data = $this->invoiceRepo->addService($request);
        ResponseData($data);
    }

    public function endService(Request $request)
    {
        $data = $this->invoiceRepo->endService($request);
        ResponseData($data);
    }

    public function settleInvoice(InvoicePaidRequest $request)
    {
        $this->invoiceRepo->paidInvoice($request);
    }

    public function clearInvoice(Request $request)
    {
        if (!isset($request->entity_id)) {
            ResponseMessage('Something is wrong', 419);
        }
        $entityId = $request->entity_id;
        // DB::beginTransaction();
        // try {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('invoices')->truncate();
        DB::table('invoice_sessions')->truncate();
        DB::table('room_sessions')->truncate();
        DB::table('invoice_services')->truncate();
        DB::table('invoice_accessories')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        if ($entityId == 0) {
            Entity::orderBy('id', 'desc')
                ->when($entityId == 0, function ($q) {
                    $q->where('id', '>', 0);
                })
                ->when($entityId != 0, function ($q) use ($entityId) {
                    $q->where('id', $entityId);
                })
                ->update(
                    [
                        'is_active' => 0,
                        'status' => 'inactive',
                    ]
                );
            EntitySession::orderBy('id', 'desc')
                ->when($entityId == 0, function ($q) {
                    $q->where('id', '>', 0);
                })
                ->when($entityId != 0, function ($q) use ($entityId) {
                    $q->where('entity_id', $entityId);
                })
                ->update([
                    'is_active' => 0,
                ]);
        }
        // DB::commit();
        Responsemessage('Successfully');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     ResponseMessage($e->getMessage(), 422);
        //     throw $e;
        // }
    }
    public function getCustomerDeposits(Request $request)
    {
        $data = $this->invoiceRepo->getCustomerDeposits($request);
        ResponseData($data);
    }

    public function cashierConfirm(Request $request, $customerDepositId)
    {
        $data = $this->invoiceRepo->cashierConfirm($request, $customerDepositId);
        ResponseData($data);
    }
}

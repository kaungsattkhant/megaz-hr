<?php

namespace App\Repositories\Invoice;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Entity;
use App\Models\HeadCount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Package;
use App\Models\RoomDiscount;
use App\Models\RoomSession;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Invoice::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;

            if ($request->date) {
                $invoices = Invoice::with('customer', 'room')
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->skip($skip)
                    ->take($perPage)
                    ->get();
            } else {
                $invoices = Invoice::with('customer', 'room')
                    ->orderBy('created_at', 'desc')
                    ->skip($skip)
                    ->take($perPage)
                    ->get();
            }
            $paginationData = MakePaginationData($request, $totalCount, 'invoices');
            $paginationData['invoices'] = $invoices;
            $paginationData = MakePaginationData($request, $totalCount, 'invoices');
            $paginationData['invoices'] = $invoices;

            return $paginationData;
        } else {
            if ($request->date) {
                $invoices = Invoice::with('customer', 'room')
                    ->whereBetween('created_at', [$request->date . ' 00:00:00', $request->date . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $invoices = Invoice::with('customer', 'room')->get();
            }

            return $invoices;
        }
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $entity = Entity::find($data['entity_id']);
            $data['area_id'] = $entity->area_id;
            $headCount = $this->headCountCreate($data);
            $data['head_count_id'] = $headCount->id;
            $data['created_by'] = UserData()->id;

            if ($data['type'] == 'package') {
                $package = Package::find($data['package_id']);
                if(!$package)
                {
                    ResponseMessage('Package not found',403);
                }
                $rooms = $package->rooms()->pluck('id');
                $roomExists = $rooms->contains($entity->id);
                if ($roomExists == false) {
                    ResponseMessage('Selected Package cannot used by chosen room');
                }
                $invoiceDate = Carbon::parse($data['invoice_date']);
                if ($invoiceDate->lt($package->from_date) || $invoiceDate->gt($package->to_date)) {
                    ResponseMessage('The selected package is not available for the given date.');
                }

                $end_date = Carbon::parse($data['invoice_date'])->addHours($package->session);
                $data['total_session_price'] = 0;
                $data['paid_amount'] = $package->price;
                $data['package_id'] = $package->id;
                $data['session_duration'] = $package->session; // nullable
                $data['price'] = 0;
                $data['invoice_type'] = 'package';
                $data['total'] = $package->price;
            } else if ($data['type'] == 'session') {

                $end_date = Carbon::parse($data['invoice_date'])->addMinutes($data['session_duration'] * 60);
                $data['total_session_price'] = $data['session_duration'] * $entity->price_per_hour;
                $data['price'] = $data['total_session_price'];
                $data['total'] = $data['total_session_price'];
                $data['invoice_type'] = 'session';
            } else if ($data['type'] == 'endless_time') {
                $data['invoice_type'] = 'endless_time';
                $end_date = null;
            }


            if ($end_date == null) {
                $data['end_date'] = null;
            } else {
                $data['end_date'] = $end_date->format('Y-m-d H:i:s');
            }
            $invoice = Invoice::create($data);
            $invoice->invoice_id = sprintf('%05d', $invoice->id);
            $invoice->save();
            $entity->is_active = 1;
            $entity->save();

            $data['invoice_id'] = $invoice->id;
            $data['entity_id'] = $entity->id;

            $data['start_date'] = $data['invoice_date'];
            $roomSession = RoomSession::create($data);
            $invoice->room_session = $roomSession;
            DB::commit();
            return $invoice;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }


    public function updateData(array $data, int $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->updaet($data);
            return $invoice;
        }

        return $invoice;
    }

    public function deleteData(int $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice->delete();
            return true;
        }
        return false;
    }

    public function headCountCreate(array $data)
    {
        $data['total_head_count'] = $data['female'] + $data['male'] + $data['child'];
        $headCount = HeadCount::create($data);
        return $headCount;
    }

    public function addSessionDuration(array $data)
    {
        DB::beginTransaction();
        try {
            $roomAndSession = RoomSession::where('invoice_id', $data['invoice_id'])->latest()->first();
            $roomSessionsWithInvoice = RoomSession::where('invoice_id', $data['invoice_id'])->get();
            $originalDuration = 0;
            foreach ($roomSessionsWithInvoice as $room_session) {
                $originalDuration += $room_session->session_duration;
            }
            $invoice = Invoice::find($data['invoice_id']);
            if ($invoice->invoice_type != 'session') {
                ResponseMessage('Room with session duration can only be added duration',422);
            }

            $invoice->total_session_price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;

            // $startTime = Carbon::parse($roomAndSession->start_date);
            // $endTime = Carbon::now();
            // $durationInMinutes = $endTime->diffInMinutes($startTime);
            // $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
            // $roundedDurationInHours = round($durationInHours, 3);
            // $roomAndSession->session_duration = $roundedDurationInHours;

            // how much duration left
            // $leftDuration = $originalDuration - $roundedDurationInHours;
            // $latestSession = $invoice->sessions->sortByDesc('created_at')->first();
            // $originalRoom = Entity::find($latestSession->entity_id);
            // $roomAndSession->price = $roundedDurationInHours * $originalRoom->price_per_hour;
            // if ($roundedDurationInHours >= 1) {
            //     $end_date = Carbon::parse(CurrentTime())->addHours($roundedDurationInHours);
            // } else {
            //     $end_date = Carbon::parse(CurrentTime())->addMinutes($roundedDurationInHours * 60);
            // }

            if ($data['session_duration'] >= 1) {
                $end_date = Carbon::parse($roomAndSession->end_date)->addHours($data['session_duration']);
            } else {
                $end_date = Carbon::parse($roomAndSession->end_date)->addMinutes($data['session_duration'] * 60);
            }
            $roomAndSession->price += $data['session_duration'] * $roomAndSession->entity->price_per_hour;
            $roomAndSession->end_date = $end_date->format('Y-m-d H:i:s');
            $roomAndSession->session_duration += $data['session_duration'];
            $roomAndSession->save();

            // $roomData['session_duration'] = $data['session_duration'] + $leftDuration;
            // $roomData['start_date'] = CurrentTime();
            // $roomData['invoice_id'] = $invoice->id;
            // $roomData['entity_id'] = $latestSession->entity_id;


            // $roomData['price'] = $originalRoom->price_per_hour * $roomData['session_duration'];

            // $roomData['end_date'] = $end_date->format('Y-m-d H:i:s');
            // $updatedDurationRoom = RoomSession::create($roomData);
            $allRooms = RoomSession::where('invoice_id', $invoice->id)->get();
            // $total_session_price = 0;
            // foreach ($allRooms as $room) {
            //     $total_session_price += $room->price;
            // }
            // $invoice->total_session_price = $total_session_price;
            // $invoice->save();
            DB::commit();
            return $roomAndSession;
        } catch (\Throwable $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function invoiceEntityChange(array $data)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::find($data['invoice_id']);
            if($invoice->invoice_type=='package')
            {
                $package = Package::find($invoice->package_id);
                $containsRoom = $package->rooms()->where('id', $data['entity_id'])->exists();
                if(!$containsRoom)
                {
                    ResponseMessage('Room cannot change because selected cannot apply package',422);
                }
            }
            $lastRoomwithInvoice = RoomSession::where('invoice_id', $invoice->id)->latest()->first();
            $startTime = Carbon::parse($lastRoomwithInvoice->start_date);
            $endTime = Carbon::now();
            $durationInMinutes = $endTime->diffInMinutes($startTime);
            $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
            $roundedDurationInHours = round($durationInHours, 3);
            $invoice = Invoice::find($lastRoomwithInvoice->invoice_id);
            $latestSession = $invoice->sessions->sortByDesc('created_at')->first();
            $latestRoomOfInvoice = Entity::find($latestSession->entity_id);
            $entity = $latestRoomOfInvoice;
            $leftDuration = $lastRoomwithInvoice->session_duration - $roundedDurationInHours;
            $lastRoomwithInvoice->session_duration = $roundedDurationInHours;
            $lastRoomwithInvoice->end_date = CurrentTime();
            if ($invoice->invoice_type == 'package') {
                $lastRoomwithInvoice->price = 0;
            } else {
                $lastRoomwithInvoice->price = $roundedDurationInHours * $entity->price_per_hour;
            }
            $lastRoomwithInvoice->save();
            $latestRoomOfInvoice->is_active = 0;
            $latestRoomOfInvoice->save();
            $room = Entity::find($data['entity_id']);
            if ($room->is_active == 1) {
                ResponseMessage('Room is currently active. Please choose another room', 422);
            }
            $room->is_active = 1;
            $room->save();
            $roomData['start_date'] = CurrentTime();
            $roomData['invoice_id'] = $invoice->id;
            $roomData['session_duration'] = $leftDuration;
            $roomData['price'] = $room->price_per_hour * $leftDuration;
            $roomData['entity_id'] = $room->id;

            if ($invoice->invoice_type == 'endless_time') {
                $roomData['end_date'] = null;
                $roomData['session_duration'] = null;
                $roomData['price'] = 0;
            } else {
                if ($leftDuration >= 1) {
                    $end_date = Carbon::parse($roomData['start_date'])->addHours($leftDuration);
                    $roomData['end_date'] = $end_date->format('Y-m-d H:i:s');
                } else {
                    $end_date = Carbon::parse($roomData['start_date'])->addMinutes($leftDuration * 60);
                    $roomData['end_date'] = $end_date->format('Y-m-d H:i:s');
                }
                if ($invoice->invoice_type == 'package') {
                    $roomData['price'] = 0;
                }
            }
            $newRoomAndSession = RoomSession::create($roomData);

            $invoice->area_id = $room->area_id;
            $invoice->save();
            $allRooms = RoomSession::where('invoice_id', $invoice->id)->get();
            $total_session_price = 0;
            foreach ($allRooms as $room) {
                $total_session_price += $room->price;
            }
            $invoice->total_session_price = $total_session_price;
            $invoice->save();
            DB::commit();

            return $room;
        } catch (\Throwable $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function doneRoom(array $data)
    {
      DB::beginTransaction();
      try{
        $invoice = Invoice::find($data['invoice_id']);
        $latestRoomSession = RoomSession::where('invoice_id', $invoice->id)->orderBy('created_at', 'desc')->first();
        $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->with(['entity'])->get();
        $total_duration = 0;
        $total_session_value = 0;
        foreach ($roomSessions as $room) {
            $total_session_value += $room->price;
            $total_duration +=$room->sessoin_duration;
        }
        $entity = Entity::find($latestRoomSession->entity_id);
        if($invoice->invoice_type=='endless_time')
        {
                $invoiceDate = Carbon::parse($invoice->invoice_date);
                $currentDate = Carbon::now();
                $minutesDifference = $currentDate->diffInMinutes($invoiceDate);
                $hoursDifference = $minutesDifference / 60;
                $hoursDifference = number_format($hoursDifference, 2);

                if($hoursDifference < 3)
                {
                    ResponseData("You can't end this room before 3 hours", 402);
                }

                $data['session_duration'] = $hoursDifference;
                $data['end_date'] = CurrentTime();
                $data['price'] = $hoursDifference * $entity->price_per_hour;

        }

        $latestRoomSession->update($data);
        DB::commit();
        ResponseData($roomSessions);
      }catch(\Exception $e)
      {
        DB::rollBack();
        ResponseMessage($e->getMessage(),422);
        throw $e;
      }

    }

    public function doneEntityWithInvoice(array $data)
    {
        $foodCharge = 0;
        $beverageCharge = 0;
        $total_session_price = 0;
        $orderDiscount = 0;
        $service_charge = 0;
        $tax = 0;
        $foodDrink = 0;
        $discount_value = 0;
        $room_discount_value= 0;


        if (isset($data['order_categories'])) {
            $data['order_categories'] = json_decode($data['order_categories'], true);
            foreach ($data['order_categories'] as $menu) {
                if ($menu['menu_category_id'] == 1 || $menu['menu_category_id'] == 2 || $menu['menu_category_id'] == 3) {
                    $foodCharge += $menu['price'];
                } else {
                    $beverageCharge += $menu['price'];
                }
            }
        }

        $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->get();
        foreach ($roomSessions as $room) {
            $total_session_price += $room->price;
        }

        $invoice = Invoice::find($data['invoice_id']);
        $order = Order::where('invoice_id', $invoice->id)->first();
        if($order)
        {
            $orderDiscount = $order->total_discount_price;
        }

        $lastRoomwithInvoice = RoomSession::where('invoice_id', $invoice->id)->latest()->first();
        $entity = Entity::find($lastRoomwithInvoice->entity_id);

        // service_charge
        if ($data['service_charge'] === true || strtolower($data['service_charge']) === 'true') {
            $service_charge = ($foodCharge + $beverageCharge) * 0.05;
        } else if (strtolower($data['service_charge']) === 'false') {
            $service_charge = 0;
        } else {
            $service_charge = 0;
        }

        // tax
        if ($data['tax'] === true || strtolower($data['tax']) === 'true') {
            $tax = round(($foodCharge + $beverageCharge + $total_session_price) * 0.05);
        } else if (strtolower($data['tax']) === 'false') {
            $tax = 0;
        } else {
            $tax = 0;
        }

        // all granted,food charge,beverage charge, total_session_price, orderDiscount, service_charge, tax

        if ($invoice->invoice_type == 'package') {
           $foodDrink = $foodCharge + $beverageCharge;
           $foodDrink =$foodDrink - $orderDiscount;
            $data['total'] =($foodDrink + $invoice->paid_amount + $tax + $service_charge) - $orderDiscount;

        } else if ($invoice->invoice_type == 'session') {
               $foodDrink = $foodCharge + $beverageCharge;
               $foodDrink =$foodDrink - $orderDiscount ;
                $data['total'] =($foodDrink + $total_session_price + $service_charge +$tax) - $orderDiscount;
                $data['total_session_price'] = $total_session_price;
        } else if($invoice->invoice_type =='endless_time') {

                $lastRoomwithInvoice->end_date = CurrentTime();
                $lastRoomwithInvoice->save();
               $foodDrink = $foodCharge + $beverageCharge;
               $foodDrink =$foodDrink - $orderDiscount ;
                $data['total'] =($foodDrink + $total_session_price + $service_charge + $tax) - $orderDiscount;
                $data['total_session_price'] = $total_session_price;
        }

        if (isset($data['discount_type'])) {
            if ($data['discount_type']) {
                if ($data['discount_type'] == 'fix_amount') {
                    $data['discount_value'] = $data['discount_value'];
                    $data['discount_type'] = 'fix_amount';
                } else if ($data['discount_type'] == 'percentage') {
                    if ($data['discount_percentage'] < 0 || $data['discount_percentage'] > 100) {
                        ResponseMessage('Discount percentage should be between 0 and 100');
                    }
                    $data['discount_value'] = ($data['food_charge'] + $total_session_price) *( $data['discount_percentage'] / 100);
                    $data['discount_type'] = 'percentage';
                }else if($data['discount_type'] == 'room_discount')
                {
                    if($invoice->invoice_type =='package'){
                        ResponseMessage('Room with packages can not have discount',402);
                    }else
                    {
                        $roomDiscount = RoomDiscount::find($data['room_discount_id']);
                        if (!$roomDiscount->rooms->contains($entity->id)) {
                            ResponseMessage('Selected discount cannot be applied', 422);
                        }

                            if($roomDiscount->session <= $lastRoomwithInvoice->session_duration)
                            {
                                if($invoice->invoice_type=='endless_time')
                                {

                                    $room_discount_value = $total_session_price - $data['room_discount_amount'];

                                }

                            }else{
                                ResponseMessage('Discount cannot be applied',422);
                            }


                    }
                }
            }
        }
        // dd($discount_value);
        $data['total'] -= ($discount_value + $room_discount_value);
        $data['tax'] = $tax;
        $data['service_charge'] = $tax;
        $data['total_session_price'] = $total_session_price;
        $data['discount_type'] = $invoice->discount_type;
        $data['order_discount_value'] = $orderDiscount;
        $data['sub_total'] =$foodDrink + $total_session_price;
        $data['payment_status'] = 'received';
        $data['complete_date'] = CurrentTime();
        $entity->is_active = 0;
        $entity->save();
        $invoice->update($data);
        $debit_total = 0;
        DB::commit(); //testign purpose need to delete
        return $invoice; //testign purpose need to delete

    }
}

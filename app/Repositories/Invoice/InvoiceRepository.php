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
                $data['price'] = $package->price;
                $data['invoice_type'] = 'package';
            } else if ($data['type'] == 'session') {

                $end_date = Carbon::parse($data['invoice_date'])->addMinutes($data['session_duration'] * 60);
                $data['total_session_price'] = $data['session_duration'] * $entity->price_per_hour;
                $data['price'] = $data['total_session_price'];
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
            if ($invoice->invoice_type = !'session') {
                ResponseMessage('Room with session duration can only added duration');
            }

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
            if ($invoice->invoice_type == 'endless_time') {
                $lastRoomwithInvoice->price = 0;
            } elseif ($invoice->invoice_type == 'package') {
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

    public function doneEntityWithInvoice(array $data)
    {
        $foodCharge = 0;
        $beverageCharge = 0;
        $endlessTimeSessionDuration = 0;

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
        $total_session_price = 0;
        foreach ($roomSessions as $room) {
            $total_session_price += $room->price;
        }

        $invoice = Invoice::find($data['invoice_id']);
        $orderDiscount = 0;
        $order = Order::where('invoice_id', $invoice->id)->first();
        if($order)
        {
            $orderDiscount = $order->total_discount_price;
        }

        $lastRoomwithInvoice = RoomSession::where('invoice_id', $invoice->id)->latest()->first();
        $latestSession = $invoice->sessions->sortByDesc('created_at')->first();
        $entity = Entity::find($latestSession->entity_id);
        $entity->is_active = 0;
        $entity->save();

        // service_charge
        if ($data['service_charge'] === true || strtolower($data['service_charge']) === 'true') {
            $data['service_charge'] = ($foodCharge + $beverageCharge) * 0.05;
        } else if (strtolower($data['service_charge']) === 'false') {
            $data['service_charge'] = 0;
        } else {
            $data['service_charge'] = 0;
        }

        // tax
        if ($data['tax'] === true || strtolower($data['tax']) === 'true') {
            $data['tax'] = round(($foodCharge + $beverageCharge + $total_session_price) * 0.05);
        } else if (strtolower($data['tax']) === 'false') {
            $data['tax'] = 0;
        } else {
            $data['tax'] = 0;
        }
        if ($invoice->invoice_type == 'package') {
            $data['food_charge'] = $foodCharge + $beverageCharge;
            $data['food_charge'] = $data['food_charge'] - $orderDiscount;
            $data['total'] = $data['food_charge'];
            $total_session_price = 0;
            $data['total_session_price'] = 0;
        } else if ($invoice->invoice_type == 'session') {
                $data['food_charge'] = $foodCharge + $beverageCharge;
                $data['food_charge'] = $data['food_charge'] - $orderDiscount;
                $data['total'] = $data['food_charge'] + $total_session_price + $data['service_charge'] + $data['tax'];
                $data['total_session_price'] = $total_session_price;
        } else if($invoice->invoice_type =='endless_time') {

                $invoiceDate = Carbon::parse($invoice->invoice_date);
                $currentDate = Carbon::now();
                $minutesDifference = $currentDate->diffInMinutes($invoiceDate);
                $hoursDifference = $minutesDifference / 60;
                $hoursDifference = number_format($hoursDifference, 2);
                if($hoursDifference < 3)
                {
                    ResponseData("You can't end this room before 3 hours", 402);
                }
                $latestSession->session_duration = $hoursDifference;
                $endlessTimeSessionDuration = $hoursDifference;
                $sessionPrice = $hoursDifference * $entity->price_per_hour;
                $latestSession->price =$sessionPrice;
                $total_session_price = $sessionPrice;
                $latestSession->end_date = CurrentTime();
                $latestSession->save();
                $data['food_charge'] = $foodCharge + $beverageCharge;
                $data['food_charge'] = $data['food_charge'] - $orderDiscount;
                $data['total'] = $data['food_charge'] + $sessionPrice + $data['service_charge'] + $data['tax'];
                $data['total_session_price'] = $sessionPrice;
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
                        if($data['is_room_discount'] == 1)
                        {
                            if($roomDiscount->session <= $latestSession->session_duration)
                            {
                                if($invoice->invoice_type=='endless_time')
                                {
                                    $latestSession->session_duration = $endlessTimeSessionDuration;
                                    $caculation= floor($endlessTimeSessionDuration/$roomDiscount->session);
                                    $latestSession->discount_session = $caculation;
                                    $latestSession->update();
                                    $entity->is_active=1;
                                    DB::commit();
                                    $data['invoice'] = 'room_discount';
                                    ResponseMessage('Enjoy your discount',200);

                                }else{
                                    // dd($latestSession->session_duration,floor($latestSession->session_duration/$roomDiscount->session),$latestSession->discount_session);
                                    $durationValue =floor($latestSession->session_duration/$roomDiscount->session);
                                    $latestSession->session_duration += $durationValue;
                                    $latestSession->discount_session = $durationValue;
                                    $latestSession->update();
                                    $entity->is_active=1;
                                    DB::commit();
                                    $data['invoice'] = 'room_discount';
                                    ResponseMessage('Enjoy your discount',200);

                                }
                            }else{
                                ResponseMessage('Discount cannot be applied',422);
                            }
                        }else{
                            $data['discount_type'] = 'room_discount';
                        }

                    }
                }
            }
        }
        $data['total'] = $data['food_charge'] + $total_session_price + $data['service_charge'] + $data['tax'];
        if($invoice->invoice_type=='package')
        {
            $data['total'] = 0;
            $data['tax'] = 0;
            $data['service_charge'] = 0;
        }
        $data['order_discount_value'] = $orderDiscount;
        $data['sub_total'] = $data['food_charge'] + $data['total_session_price'];
        $data['payment_status'] = 'received';
        $data['complete_date'] = CurrentTime();

        $invoice->update($data);
        $debit_total = 0;
        DB::commit(); //testign purpose need to delete
        return $invoice; //testign purpose need to delete

    }
}

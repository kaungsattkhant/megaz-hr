<?php

namespace App\Repositories\Invoice;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use Illuminate\Http\Request;

use App\Models\Entity;
use App\Models\HeadCount;
use App\Models\Invoice;
use App\Models\RoomSession;

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
        $entity = Entity::find($data['entity_id']);
        $data['area_id'] = $entity->area_id;
        $headCount = $this->headCountCreate($data);
        $data['head_count_id'] = $headCount->id;
        $invoice = Invoice::create($data);

        $invoice->invoice_id = sprintf('%05d', $invoice->id);
        $invoice->save();

        $entity->is_active = 1;
        $entity->save();

        $data['invoice_id'] = $invoice->id;
        $data['start_date'] = $data['invoice_date'];
        if ($data['session_duration'] >= 1) {
            $end_date = Carbon::parse($data['start_date'])->addHours($data['session_duration']);
        } else {
            $end_date = Carbon::parse($data['start_date'])->addMinutes($data['session_duration'] * 60);
        }
        $data['entity_id'] = $invoice->entity_id;
        $data['end_date'] = $end_date->format('Y-m-d H:i:s');
        $data['price'] = $entity->price_per_hour * $data['session_duration'];
        $roomSession = RoomSession::create($data);
        $invoice->room_session = $roomSession;

        return $invoice;
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
        // current Room
        $roomAndSession = RoomSession::where('invoice_id', $data['invoice_id'])->latest()->first();
        // past room and changes of room
        $roomSessionsWithInvoice = RoomSession::where('invoice_id', $data['invoice_id'])->get();
        $originalDuration = 0;
        foreach ($roomSessionsWithInvoice as $room_session) {
            $originalDuration += $room_session->session_duration;
        }
        $invoice = Invoice::find($data['invoice_id']);

        // caculating last room duration

        $startTime = Carbon::parse($roomAndSession->start_date);
        $endTime = Carbon::now();
        $durationInMinutes = $endTime->diffInMinutes($startTime);
        $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
        $roundedDurationInHours = round($durationInHours, 3);

        // $startTime = Carbon::parse($roomAndSession->start_date);
        // $endTime = Carbon::now();
        // $duration = $endTime->diffInMinutes($startTime);
        // $hours = intdiv($duration, 60);
        // $minutes = $duration % 60;
        // $decimalHours = $hours + ($minutes / 60);
        // $roundedDecimalHours = round($decimalHours, 3);
        $roomAndSession->session_duration = $roundedDurationInHours;

        // how much duration left
        $leftDuration = $originalDuration - $roundedDurationInHours;
        $originalRoom = Entity::find($invoice->entity_id);
        $roomAndSession->price = $roundedDurationInHours * $originalRoom->price_per_hour;
        if ($roundedDurationInHours >= 1) {
            $end_date = Carbon::parse(CurrentTime())->addHours($roundedDurationInHours);
        } else {
            $end_date = Carbon::parse(CurrentTime())->addMinutes($roundedDurationInHours * 60);
        }
        $roomAndSession->end_date = $end_date;
        $roomAndSession->save();

        $roomData['session_duration'] = $data['session_duration'] + $leftDuration;
        $roomData['start_date'] = CurrentTime();
        $roomData['invoice_id'] = $invoice->id;

        // if($data['charge']==true)
        // {
        // }else{
        //     $roomData['price'] = 0;
        // }
        $roomData['entity_id'] = $invoice->entity_id;
        $roomData['price'] = $originalRoom->price_per_hour * $data['session_duration'];

        if ($data['session_duration'] >= 1) {
            $end_date = Carbon::parse($roomData['start_date'])->addHours($data['session_duration']);
        } else {
            $end_date = Carbon::parse($roomData['start_date'])->addMinutes($data['session_duration'] * 60);
        }

        $roomData['end_date'] = $end_date->format('Y-m-d H:i:s');
        $updatedDurationRoom = RoomSession::create($roomData);

        return $roomAndSession;
    }

    public function invoiceEntityChange(array $data)
    {
        $invoice = Invoice::find($data['invoice_id']);
        $lastRoomwithInvoice = RoomSession::where('invoice_id', $invoice->id)->latest()->first();
        $startTime = Carbon::parse($lastRoomwithInvoice->start_date);
        $endTime = Carbon::now();
        $durationInMinutes = $endTime->diffInMinutes($startTime);
        $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
        $roundedDurationInHours = round($durationInHours, 3);
        $invoice = Invoice::find($lastRoomwithInvoice->invoice_id);
        $entity = $invoice->room;
        $leftDuration = $lastRoomwithInvoice->session_duration - $roundedDurationInHours;
        $lastRoomwithInvoice->session_duration = $roundedDurationInHours;
        $lastRoomwithInvoice->end_date = CurrentTime();
        $lastRoomwithInvoice->price = $roundedDurationInHours * $entity->price_per_hour;
        $lastRoomwithInvoice->save();

        $originalRoom = Entity::find($invoice->entity_id);
        $originalRoom->is_active = 0;
        $originalRoom->save();
        $room = Entity::find($data['entity_id']);
        $room->is_active = 1;
        $room->save();

        $roomData['start_date'] = CurrentTime();
        $roomData['invoice_id'] = $invoice->id;
        $roomData['session_duration'] = $leftDuration;
        $roomData['price'] = $room->price_per_hour * $leftDuration;
        $roomData['entity_id'] = $room->id;

        if ($leftDuration >= 1) {
            $end_date = Carbon::parse($roomData['start_date'])->addHours($leftDuration);
        } else {
            $end_date = Carbon::parse($roomData['start_date'])->addMinutes($leftDuration * 60);
        }

        $roomData['end_date'] = $end_date->format('Y-m-d H:i:s');
        $newRoomAndSession = RoomSession::create($roomData);

        $invoice->entity_id = $room->id;
        $invoice->area_id = $room->area_id;
        $invoice->save();

        return $room;
    }

    public function doneEntityWithInvoice(array $data)
    {
        $foodCharge = 0;
        $beverageCharge = 0;
        foreach ($data['order_categories'] as $menu) {
            if ($menu['menu_category_id'] == 1 || $menu['menu_category_id'] == 2 || $menu['menu_category_id'] == 3) {
                $foodCharge += $menu['price'];
            } else {
                $beverageCharge += $menu['price'];
            }
        }

        $roomSessions = RoomSession::where('invoice_id', $data['invoice_id'])->get();
        $total_session_price = 0;
        foreach ($roomSessions as $room) {
            $total_session_price += $room->price;
        }

        $invoice = Invoice::find($data['invoice_id']);
        $entity = Entity::find($invoice->entity_id);
        $entity->is_active = 0;
        $entity->save();

        if ($data['service_charge'] == true) {
            $data['service_charge'] = ($foodCharge + $beverageCharge) * 0.05;
        } else {
            $data['service_charge'] = 0;
        }

        if ($data['tax'] == true) {
            $data['tax'] = round(($foodCharge + $beverageCharge + $total_session_price) * 0.05);
        } else {
            $data['tax'] = 0;
        }

        $data['food_charge'] = $foodCharge + $beverageCharge;
        $data['total'] = $data['food_charge'] + $total_session_price + $data['service_charge'] + $data['tax'];
        $data['total_session_price'] = $total_session_price;
        $data['sub_total'] = $data['food_charge'] + $data['total_session_price'];
        $data['payment_status'] = 'received';
        $data['complete_date'] = CurrentTime();
        $invoice->update($data);

        // transaction and ledgers
        if($data['payment_type']=='cash')
        {
            $posBook = Account::where('account_code','2-1011')->get()->first();
        }else{
            $posBook = Account::where('account_code','2-1012')->get()->first();
        }
        $foodKtvAcc = Account::where('account_code','5-0101')->first();

        $data['date'] = now();
        $data['created_by'] = 1;//example
        $data['transactionable_id'] = $invoice->id;
        $data['transactionable_type'] = 'invoice';

        $foodTransaction=(new StoreTransactionLedger())->createTransaction($data);

        $foodCreditLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $foodCharge,
            'transaction_id' => $foodTransaction->id,
            'account_id' => $foodKtvAcc->id,
            'action' => 'credit',
        ]);

        $foodCashDebit = (new StoreTransactionLedger())->storeLedger([
            'value' => $foodCharge,
            'transaction_id' => $foodTransaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
        ]);

        $beverageKtvAcc = Account::where('account_code','5-0102')->get()->first();

        $data['date'] = now();
        $data['created_by'] = 1;//example
        $data['transactionable_id'] = $invoice->id;
        $data['transactionable_type'] = 'invoice';

        $beverageTransaction =(new StoreTransactionLedger())->createTransaction($data);

        $foodCreditLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $beverageCharge,
            'transaction_id' => $beverageTransaction->id,
            'account_id' => $beverageKtvAcc->id,
            'action' => 'credit',
        ]);

        $beverageCashDebit = (new StoreTransactionLedger())->storeLedger([
            'value' => $beverageCharge,
            'transaction_id' => $beverageTransaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
        ]);


        $ktvRoomCharges = Account::where('account_code','5-0103')->get()->first();

        $data['date'] = now();
        $data['created_by'] = 1;//example
        $data['transactionable_id'] = $invoice->id;
        $data['transactionable_type'] = 'invoice';

        $ktvRoomTransaction =(new StoreTransactionLedger())->createTransaction($data);

        $ktvRoomLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $total_session_price,
            'transaction_id' => $ktvRoomTransaction->id,
            'account_id' => $ktvRoomCharges->id,
            'action' => 'credit',
        ]);

        $ktvCashDebit = (new StoreTransactionLedger())->storeLedger([
            'value' => $total_session_price,
            'transaction_id' => $ktvRoomTransaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
        ]);

        $serviceMoneyAcc =Account::where('account_code','6-2009')->get()->first();

        $data['date'] = now();
        $data['created_by'] = 1;//example
        $data['transactionable_id'] = $invoice->id;
        $data['transactionable_type'] = 'invoice';

        $serviceMoneyTransaction =(new StoreTransactionLedger())->createTransaction($data);

        $serviceLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $data['service_charge'],
            'transaction_id' => $serviceMoneyTransaction->id,
            'account_id' => $serviceMoneyAcc->id,
            'action' => 'credit',
        ]);

        $serviceCashDebit = (new StoreTransactionLedger())->storeLedger([
            'value' => $data['service_charge'],
            'transaction_id' => $serviceMoneyTransaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
        ]);

        $taxAcc = Account::where('account_code','6-9002')->get()->first();

        $data['date'] = now();
        $data['created_by'] = 1;//example
        $data['transactionable_id'] = $invoice->id;
        $data['transactionable_type'] = 'invoice';

        $taxTransaction =(new StoreTransactionLedger())->createTransaction($data);

        $taxLedger = (new StoreTransactionLedger())->storeLedger([
            'value' => $data['tax'],
            'transaction_id' => $taxTransaction->id,
            'account_id' => $taxAcc->id,
            'action' => 'credit',
        ]);

        $textDebit = (new StoreTransactionLedger())->storeLedger([
            'value' => $data['tax'],
            'transaction_id' => $taxTransaction->id,
            'account_id' => $posBook->id,
            'action' => 'debit',
        ]);
        if($data['discount_value'])
        {
            $discountAcc = Account::where('account_code','6-2003')->get()->first();
            $data['date'] = now();
            $data['created_by'] = 1;//example
            $data['transactionable_id'] = $invoice->id;
            $data['transactionable_type'] = 'invoice';

            $discountTransaction =(new StoreTransactionLedger())->createTransaction($data);

            $discountDebit = (new StoreTransactionLedger())->storeLedger([
                'value' => $data['discount_value'],
                'transaction_id' => $discountTransaction->id,
                'account_id' => $discountAcc->id,
                'action' => 'debit',
            ]);

            $discountCashCredit = (new StoreTransactionLedger())->storeLedger([
                'value' => $data['discount_value'],
                'transaction_id' => $discountTransaction->id,
                'account_id' => $posBook->id,
                'action' => 'credit',
            ]);
        }

        return $invoice;
    }
}

<?php

namespace App\Repositories\Invoice;

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
        $data['entity_id'] =$invoice->entity_id;
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
        $roomSessionsWithInvoice = RoomSession::where('invoice_id',$data['invoice_id'])->get();
        $originalDuration = 0;
        foreach($roomSessionsWithInvoice as $room_session)
        {
            $originalDuration += $room_session->session_duration;
        }
        $invoice = Invoice::find($data['invoice_id']);

        // caculating
        $startTime = Carbon::parse($roomAndSession->start_date);
        dd($roomAndSession);
        $endTime = Carbon::now();
        $duration = $endTime->diffInMinutes($startTime);
        $hours = intdiv($duration, 60);
        $minutes = $duration % 60;
        $decimalHours = $hours + ($minutes / 60);
        $roundedDecimalHours = round($decimalHours, 3);
        $roomAndSession->session_duration = $roundedDecimalHours;

        $leftDuration = $originalDuration - $roundedDecimalHours;
        $originalRoom = Entity::find($invoice->entity_id);
        $roomAndSession->price = $roundedDecimalHours * $originalRoom->price_per_hour;
        if ($roundedDecimalHours >= 1) {
            $end_date = Carbon::parse(CurrentTime())->addHours($roundedDecimalHours);
        } else {
            $end_date = Carbon::parse(CurrentTime())->addMinutes($roundedDecimalHours * 60);
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

        // $endDate = Carbon::parse($roomAndSession->end_date);
        // $endDate->addHours($data['session_duration']);
        // $roomAndSession->end_date = $endDate;
        // $roomAndSession->session_duration += $data['session_duration'];
        // $endDate = $roomAndSession->end_date;
        // $roomAndSession->save();
        return $roomAndSession;
    }

    public function invoiceEntityChange(array $data)
    {
        $invoice = Invoice::find($data['invoice_id']);
        $lastRoomwithInvoice = RoomSession::where('invoice_id', $invoice->id)->latest()->first();
            $startTime = Carbon::parse($lastRoomwithInvoice->start_date);
            $endTime = Carbon::now();

            $duration = $endTime->diffInMinutes($startTime);
            $hours = intdiv($duration, 60);
            $minutes = $duration % 60;
            $decimalHours = $hours + ($minutes / 60);
            $roundedDecimalHours = round($decimalHours, 3);
        $invoice = Invoice::find($lastRoomwithInvoice->invoice_id);
        $entity = $invoice->room;
        $leftDuration = $lastRoomwithInvoice->session_duration- $roundedDecimalHours;
        $lastRoomwithInvoice->session_duration = $roundedDecimalHours;
        $lastRoomwithInvoice->end_date = CurrentTime();
        $lastRoomwithInvoice->price = $roundedDecimalHours * $entity->price_per_hour;
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
        $invoice = Invoice::find($data['invoice_id']);
        $entity = Entity::find($invoice->entity_id);
        $entity->is_active = 0;
        $entity->save();
        $data['sub_total'] = $data['food_charge'] + $data['total_session_price'];
        $data['payment_status'] = 'received';
        $data['complete_date'] = CurrentTime();
        $invoice->update($data);
        return $invoice;
    }
}

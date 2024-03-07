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
        if($request->per_page || $request->page){
            $totalCount = Invoice::count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $invoices = Invoice::skip($skip)->take($perPage)->get();
            $paginationData = MakePaginationData($request, $totalCount, 'invoices');
            $paginationData['invoices'] = $invoices;

            return $paginationData;
        }
        else{
            $invoices = Invoice::all();

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
        $data['end_date'] = Carbon::parse($data['start_date'])
        ->addHours($data['session_duration'])
        ->format('Y-m-d H:i:s');
        $roomSession = RoomSession::create($data);
        $invoice->room_session = $roomSession;

        return $invoice;
    }

    public function updateData(array $data,int $id)
    {
        $invoice = Invoice::find($id);
        if($invoice)
        {
            $invoice->updaet($data);
            return $invoice;
        }

        return $invoice;
    }

    public function deleteData(int $id)
    {
        $invoice = Invoice::find($id);
        if($invoice)
        {
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
        $roomAndSession = RoomSession::where('invoice_id',$data['invoice_id'])->get()->first();
        $endDate = Carbon::parse($roomAndSession->end_date);
        $endDate->addHours($data['session_duration']);
        $roomAndSession->end_date = $endDate;
        $roomAndSession->session_duration += $data['session_duration'];
        $endDate = $roomAndSession->end_date;
        $roomAndSession->save();
        return $roomAndSession;
    }

    public function invoiceEntityChange(array $data)
    {
        $invoice = Invoice::find($data['invoice_id']);

        $originalRoom = Entity::find($invoice->entity_id);
        $originalRoom->is_active = 0;
        $originalRoom->save();
        $room = Entity::find($data['entity_id']);
        $room->is_active = 1;
        // dd($originalRoom->name,$room->area_id);
        $room->save();

        $invoice->entity_id = $room->id;
        $invoice->area_id = $room->area_id;
        $invoice->save();
        return $room;

    }
}

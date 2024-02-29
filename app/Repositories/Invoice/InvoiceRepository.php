<?php

namespace App\Repositories\Invoice;

use App\Models\Entity;
use App\Models\HeadCount;
use App\Models\Invoice;
use Illuminate\Http\Request;

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
        $data['area_id'] = Entity::find($data['entity_id'])->area_id;
        $headCount = $this->headCountCreate($data);
        $data['head_count_id'] = $headCount->id;
        $invoice = Invoice::create($data);
        $invoice->invoice_id = $invoice->id;
        $invoice->save();
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
}

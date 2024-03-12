<?php

namespace App\Repositories\Invoice;

use Illuminate\Http\Request;

interface InvoiceRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);

    public function addSessionDuration(array $data);

    public function invoiceEntityChange(array $data);

    public function doneEntityWithInvoice(array $data);
}


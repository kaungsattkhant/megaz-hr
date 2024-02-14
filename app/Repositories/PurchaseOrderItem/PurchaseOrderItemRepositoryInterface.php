<?php

namespace App\Repositories\PurchaseOrderItem;

use Illuminate\Http\Request;

interface PurchaseOrderItemRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);
}

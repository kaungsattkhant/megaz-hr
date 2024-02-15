<?php

namespace App\Repositories\PurchaseOrder;

use Illuminate\Http\Request;

interface PurchaseOrderRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $purchaseOrder,array $purchaseOrderItems);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);

    public function updateKitchenAndFinancePO(string $condition, array $data ,int $id);


}

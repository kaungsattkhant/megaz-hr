<?php

namespace App\Repositories\PurchaseOrder;

use Illuminate\Http\Request;

interface PurchaseOrderRepositoryInterface
{
    public function listAllData(Request $request);

    public function createOrUpdate($request);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function updateKitchenAndFinancePO(string $condition, array $data, int $id);

    public function detail($model);

    public function deletePurchaseOrderItem($id);

    public function updateIsCheck($request);

    public function getPurchaseOrderItemConfirmationList($request);

    public function confirmPurchaseOrderItem($request);

    public function getAvgPriceByBrand($itemId, $brandId);
}

<?php

namespace App\Repositories\Item;

use Illuminate\Http\Request;

interface ItemRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function addPriceItem($request);

    public function getItemPriceListByItem($item_id);

    public function getItemType();

    public function supplierByItem($itemId);

    public function brandBySupplier($request, $itemId, $supplierId);

    public function itemImport($request);
}

<?php

namespace App\Repositories\Item;

use Illuminate\Http\Request;

interface ItemRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);

    public function addPriceItem(array $data, int $id);

    public function getItemPriceListByItem($item_id);

    public function getItemType();

}

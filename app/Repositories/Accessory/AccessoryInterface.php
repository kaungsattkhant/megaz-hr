<?php

namespace App\Repositories\Accessory;

use Illuminate\Http\Request;

interface AccessoryInterface
{
    public function list($request);
    public function store($request);
    public function detail($accessory);
    public function deleteAccessoryItem($id);
    public function getAccessoryByCategory($accessory_category_id);
    public function createInvoiceAccessory($request);


}
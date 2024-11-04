<?php

namespace App\Repositories\Accessory;

use Illuminate\Http\Request;

interface AccessoryInterface
{
    public function list($request);

    public function store($request);

    public function detail($accessory);

    public function deletAccessoryItem($id);

}
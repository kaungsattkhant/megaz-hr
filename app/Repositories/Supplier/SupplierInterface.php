<?php

namespace App\Repositories\Supplier;

use Illuminate\Http\Request;

interface SupplierInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($supplier);
}
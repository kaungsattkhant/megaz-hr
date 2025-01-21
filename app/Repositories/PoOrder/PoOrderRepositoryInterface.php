<?php

namespace App\Repositories\PoOrder;

use Illuminate\Http\Request;

interface PoOrderRepositoryInterface
{

  public function getPoOrderItems(Request $request);
  public function test($request);
  public function  storePoOrderItems($validatedData);
}

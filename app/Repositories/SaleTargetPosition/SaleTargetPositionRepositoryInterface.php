<?php

namespace App\Repositories\SaleTargetPosition;

use Illuminate\Http\Request;

interface SaleTargetPositionRepositoryInterface
{

    public function createSaleTarget(Request $request);

    public function updateSaleTarget(Request $request, int $id);

    public function deleteSaleTarget(int $id);

    public function saleTargetPositionList();

    public function saleTargetPositionDetail(int $id);

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SaleTargetPosition;
use App\Repositories\SaleTargetPosition\SaleTargetPositionRepositoryInterface;
use Illuminate\Http\Request;

class SaleTargetPositionAPIController extends Controller
{
    //
    protected $saleTargetPositionRepo;
    public function __construct(SaleTargetPositionRepositoryInterface $saleTargetPositionRepo)
    {
        $this->saleTargetPositionRepo = $saleTargetPositionRepo;
    }

    public function listAllSalteTargetPosition()
    {
        $this->saleTargetPositionRepo->saleTargetPositionList();
    }

    public function createSaleTargetPosition(Request $request)
    {
        $this->saleTargetPositionRepo->createSaleTarget($request);
    }

    public function updateSaleTargetPosition(Request $request, int $id)
    {
        $this->saleTargetPositionRepo->updateSaleTarget($request, $id);
    }

    public function deleteSaleTargetPosition(int $id)
    {
        $this->saleTargetPositionRepo->deleteSaleTarget($id);
    }

    public function saleTargetPositionDetail(int $id)
    {
        $this->saleTargetPositionRepo->saleTargetPositionDetail($id);
    }
}

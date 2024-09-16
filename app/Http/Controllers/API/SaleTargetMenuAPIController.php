<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\SaleTargetMenu\SaleTargetMenuRepositoryInterface;
use Illuminate\Http\Request;

class SaleTargetMenuAPIController extends Controller
{
    //
    protected $saleTargetMenuRepo;

    public function __construct(SaleTargetMenuRepositoryInterface $saleTargetMenuRepo)
    {
        $this->saleTargetMenuRepo = $saleTargetMenuRepo;
    }

    public function listSaleTargetMenu()
    {
        $this->saleTargetMenuRepo->listSaleTargetMenu();
    }

    public function getSaleTargetMenu(int $id)
    {
        $this->saleTargetMenuRepo->getSaleTargetMenu($id);
    }

    public function createSaleTargetMenu(Request $request)
    {
        $this->saleTargetMenuRepo->createSaleTargetMenu($request);
    }

    public function updateSaleTargetMenu(Request $request, int $id)
    {
        $this->saleTargetMenuRepo->updateSaleTargetMenu($request, $id);
    }

    public function deleteSaleTargetMenu(int $id)
    {
        $this->saleTargetMenuRepo->deleteSaleTargetMenu($id);
    }
}

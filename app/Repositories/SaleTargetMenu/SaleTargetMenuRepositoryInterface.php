<?php

namespace App\Repositories\SaleTargetMenu;

use Illuminate\Http\Request;

interface SaleTargetMenuRepositoryInterface
{
    public function listSaleTargetMenu();

    public function getSaleTargetMenu(int $id);

    public function createSaleTargetMenu(Request $request);

    public function updateSaleTargetMenu(Request $request, int $id);

    public function deleteSaleTargetMenu(int $id);


}

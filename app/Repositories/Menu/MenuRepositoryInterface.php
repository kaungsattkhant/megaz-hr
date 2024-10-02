<?php

namespace App\Repositories\Menu;

use Illuminate\Http\Request;

interface MenuRepositoryInterface
{
    public function createData(array $data, array $items,array $areas);

    public function createMenuPrice(int $id, float $price);

    public function listAllData(Request $request);

    public function menuIsActive(int $id);

    public function menuDetail(int $id);

    public function editMenu(int $id, array $data, array $items,array $areas);

    public function toggleMenuFeature($id);

    public function menuAreaList($id);

    public function menuReport(Request $request);

    // user app
    public function listAllMenu(Request $request);

}

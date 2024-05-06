<?php

namespace App\Repositories\Menu;

use Illuminate\Http\Request;

interface MenuRepositoryInterface
{
    public function createData(array $data, array $items);

    public function createMenuPrice(int $id, float $price);

    public function listAllData(Request $request);

    public function menuIsActive(int $id);
}

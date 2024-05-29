<?php

namespace App\Repositories\MenuServiceDiscount;

use Illuminate\Http\Request;

interface MenuServiceDiscountRepositoryInterface
{

    public function listAllData(Request $request);

    public function createData( array $data );

    public function editData( int $id, array $data);

    public function deleteData(int $id);
}

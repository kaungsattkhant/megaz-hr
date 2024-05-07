<?php

namespace App\Repositories\Uom;

use Illuminate\Http\Request;

interface UomRepositoryInterface
{
    public function listAllData(Request $request);

    public function createUomConversion(array $data);

    public function updateData(string $name, int $id);

    public function deleteData(int $id);

    public function uomConversaionList(Request $request);

    public function updateUomConversion($data);
}

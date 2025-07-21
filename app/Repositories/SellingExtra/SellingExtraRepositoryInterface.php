<?php

namespace App\Repositories\SellingExtra;

use Illuminate\Http\Request;

interface SellingExtraRepositoryInterface
{
    public function listAllData(Request $request);

    public function find($id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);
}

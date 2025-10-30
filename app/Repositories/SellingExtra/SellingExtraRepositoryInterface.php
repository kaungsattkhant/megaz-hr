<?php

namespace App\Repositories\SellingExtra;

use Illuminate\Http\Request;

interface SellingExtraRepositoryInterface
{
    public function listAllCategories(Request $request);

    public function createCategory(array $data);

    public function listAllData(Request $request, bool $isPosQuerying = false);

    public function find($id);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);
}

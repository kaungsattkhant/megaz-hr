<?php

namespace App\Repositories\MenuCategory;

use Illuminate\Http\Request;

interface MenuCategoryRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function editData(int $id, array $data);

    public function deleteData(int $id);

    // user app
    public function listAllDataUser();
}

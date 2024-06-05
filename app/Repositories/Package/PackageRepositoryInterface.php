<?php

namespace App\Repositories\Package;

use Illuminate\Http\Request;

interface PackageRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(array $data);

    public function editData(int $id, array $data);

    public function deleteData(int $id);

    public function detailPackage(int $id);
}

<?php

namespace App\Repositories\HeadAccount;

use Illuminate\Http\Request;

interface HeadAccountInterface
{
    // public function listAllData(Request $request);

    public function updateOrCreate($request);

    // public function updateData(array $data,int $id);

    // public function deleteData(int $id);
}
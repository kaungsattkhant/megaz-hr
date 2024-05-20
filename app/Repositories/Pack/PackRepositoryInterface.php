<?php

namespace App\Repositories\Pack;

use Illuminate\Http\Request;

interface PackRepositoryInterface
{
    public function createPack(array $data);

    public function listAllData(Request $request);
}

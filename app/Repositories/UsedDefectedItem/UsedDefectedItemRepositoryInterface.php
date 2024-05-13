<?php

namespace App\Repositories\UsedDefectedItem;

use Illuminate\Http\Request;

interface UsedDefectedITemRepositoryInterface
{
    public function listUsedDefectList(Request $request);

    public function createData(array $data);
}
